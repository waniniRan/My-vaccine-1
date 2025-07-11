import React, { useEffect, useState } from 'react';
import { View, Text, ActivityIndicator, FlatList, StyleSheet, TouchableOpacity, ScrollView } from 'react-native';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import guardianService from '../../services/guardianService';
import { useAuth } from '../AuthContext';

const COLORS = {
  primary: '#2a5ca4',
  secondary: '#34C759',
  background: '#f5f7fa',
  card: '#fff',
  text: '#222',
  gray: '#888',
};

const VaccinationsTab = () => {
  const { user } = useAuth();
  const [children, setChildren] = useState<any[]>([]);
  const [selectedChild, setSelectedChild] = useState<any>(null);
  const [vaccinationRecords, setVaccinationRecords] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [error, setError] = useState('');

  const fetchChildrenAndVaccinations = async () => {
    setLoading(true);
    setError('');
    try {
      const childrenData = await guardianService.getChildren();
      setChildren(childrenData);
      if (childrenData.length > 0) {
        setSelectedChild(childrenData[0]);
        await fetchVaccinations(childrenData[0].child_id);
      } else {
        setSelectedChild(null);
        setVaccinationRecords([]);
      }
    } catch (err: any) {
      setError(err.detail || 'Failed to load data');
    }
    setLoading(false);
  };

  const fetchVaccinations = async (childId: string) => {
    try {
      const vaccinations = await guardianService.getChildVaccinationRecords(childId);
      setVaccinationRecords(vaccinations);
    } catch (err: any) {
      setError(err.detail || 'Failed to load vaccination records');
    }
  };

  useEffect(() => {
    fetchChildrenAndVaccinations();
  }, []);

  const onRefresh = async () => {
    setRefreshing(true);
    await fetchChildrenAndVaccinations();
    setRefreshing(false);
  };

  const handleChildSwitch = async (child: any) => {
    setSelectedChild(child);
    await fetchVaccinations(child.child_id);
  };

  if (loading) {
    return <ActivityIndicator style={{ flex: 1 }} />;
  }

  if (error) {
    return <View style={styles.center}><Text style={styles.error}>{error}</Text></View>;
  }

  return (
    <ScrollView style={{ flex: 1, backgroundColor: COLORS.background }} contentContainerStyle={{ paddingBottom: 32 }}>
      {children.length > 1 && (
        <ScrollView horizontal showsHorizontalScrollIndicator={false} style={{ marginVertical: 12, marginLeft: 8 }}>
          {children.map(child => (
            <TouchableOpacity
              key={child.child_id}
              style={[styles.childPill, selectedChild?.child_id === child.child_id && styles.childPillSelected]}
              onPress={() => handleChildSwitch(child)}
            >
              <Ionicons name="person" size={18} color={selectedChild?.child_id === child.child_id ? '#fff' : COLORS.primary} />
              <Text style={[styles.childPillText, selectedChild?.child_id === child.child_id && { color: '#fff' }]}>{child.first_name}</Text>
            </TouchableOpacity>
          ))}
        </ScrollView>
      )}
      {selectedChild ? (
        <View style={styles.childCard}>
          <Text style={styles.childName}>{selectedChild.first_name} {selectedChild.last_name}</Text>
          <Text style={styles.childInfo}>DOB: {selectedChild.date_of_birth} | Gender: {selectedChild.gender} | Age: {selectedChild.age}</Text>
          <Text style={styles.sectionTitle}>Vaccination Records</Text>
          {vaccinationRecords.length === 0 ? (
            <Text style={styles.emptyText}>No vaccination records found.</Text>
          ) : (
            <FlatList
              data={vaccinationRecords}
              keyExtractor={item => item.recordID}
              renderItem={({ item }) => (
                <View style={styles.recordCard}>
                  <MaterialCommunityIcons name="needle" size={16} color={COLORS.secondary} style={{ marginRight: 8 }} />
                  <Text style={styles.recordText}>{item.administration_date}: {item.vaccine_name} (Dose {item.dose_number})</Text>
                </View>
              )}
              scrollEnabled={false}
            />
          )}
        </View>
      ) : (
        <Text style={styles.emptyText}>No children found.</Text>
      )}
      <TouchableOpacity style={styles.refreshBtn} onPress={onRefresh}>
        <Ionicons name="refresh" size={20} color="#fff" />
        <Text style={styles.refreshBtnText}>Refresh</Text>
      </TouchableOpacity>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  childPill: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#eaf1fb', borderRadius: 20, paddingHorizontal: 16, paddingVertical: 8, marginRight: 8 },
  childPillSelected: { backgroundColor: COLORS.primary },
  childPillText: { marginLeft: 6, color: COLORS.primary, fontWeight: 'bold' },
  childCard: { backgroundColor: COLORS.card, borderRadius: 16, padding: 20, margin: 16, marginTop: 0, shadowColor: '#000', shadowOpacity: 0.04, shadowRadius: 8, elevation: 2 },
  childName: { fontSize: 18, fontWeight: 'bold', color: COLORS.primary },
  childInfo: { fontSize: 14, color: COLORS.gray, marginBottom: 10 },
  sectionTitle: { fontSize: 16, fontWeight: 'bold', color: COLORS.text, marginTop: 18, marginBottom: 6 },
  recordCard: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#f5f7fa', borderRadius: 8, padding: 10, marginBottom: 6 },
  recordText: { fontSize: 14, color: COLORS.text },
  emptyText: { color: COLORS.gray, textAlign: 'center', marginVertical: 12 },
  refreshBtn: { flexDirection: 'row', alignItems: 'center', justifyContent: 'center', backgroundColor: COLORS.primary, borderRadius: 24, paddingVertical: 12, margin: 24 },
  refreshBtnText: { color: '#fff', fontWeight: 'bold', marginLeft: 8, fontSize: 16 },
  error: { color: 'red', marginBottom: 10, textAlign: 'center' },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center' },
});

export default VaccinationsTab; 