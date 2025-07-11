import React, { useEffect, useState } from 'react';
import { View, Text, ActivityIndicator, FlatList, RefreshControl, StyleSheet, TouchableOpacity, ScrollView } from 'react-native';
import { Ionicons, FontAwesome5, MaterialCommunityIcons } from '@expo/vector-icons';
import guardianService from '../../services/guardianService';
import { useAuth } from '../AuthContext';
import StatsGrid from '../../components/ui/StatsGrid';

const COLORS = {
  primary: '#2a5ca4',
  secondary: '#34C759',
  accent: '#FF9500',
  background: '#f5f7fa',
  card: '#fff',
  text: '#222',
  gray: '#888',
};

const HomeTab = () => {
  const { user, loading: authLoading } = useAuth();
  const [children, setChildren] = useState<any[]>([]);
  const [selectedChild, setSelectedChild] = useState<any>(null);
  const [growthRecords, setGrowthRecords] = useState<any[]>([]);
  const [vaccines, setVaccines] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [error, setError] = useState('');
  const [totalChildren, setTotalChildren] = useState(0);
  const [totalVaccinations, setTotalVaccinations] = useState(0);
  const [totalGrowthRecords, setTotalGrowthRecords] = useState(0);

  const fetchData = async () => {
    setLoading(true);
    setError('');
    try {
      const childrenData = await guardianService.getChildren();
      setChildren(childrenData);
      if (childrenData.length > 0) {
        setSelectedChild(childrenData[0]);
        await fetchAllChildrenData(childrenData);
      } else {
        setSelectedChild(null);
        setGrowthRecords([]);
        setVaccines([]);
      }
    } catch (err: any) {
      setError(err.detail || 'Failed to load data');
    }
    setLoading(false);
  };

  const fetchAllChildrenData = async (childrenData: any[]) => {
    try {
      let allGrowthRecords: any[] = [];
      let allVaccinationRecords: any[] = [];
      for (const child of childrenData) {
        const growth = await guardianService.getChildGrowthRecords(child.child_id);
        allGrowthRecords = allGrowthRecords.concat(growth);
        const vaccinesData = await guardianService.getChildVaccinationRecords(child.child_id);
        allVaccinationRecords = allVaccinationRecords.concat(vaccinesData);
      }
      setGrowthRecords(allGrowthRecords);
      setVaccines(allVaccinationRecords);
    } catch (err: any) {
      setError(err.detail || 'Failed to load children data');
    }
  };

  const fetchChildDetails = async (childId: string) => {
    try {
      const growth = await guardianService.getChildGrowthRecords(childId);
      setGrowthRecords(growth);
      const vaccinesData = await guardianService.getChildVaccinationRecords(childId);
      setVaccines(vaccinesData);
    } catch (err: any) {
      setError(err.detail || 'Failed to load child details');
    }
  };

  useEffect(() => {
    fetchData();
  }, []);

  useEffect(() => {
    setTotalChildren(children.length);
    setTotalVaccinations(vaccines.length);
    setTotalGrowthRecords(growthRecords.length);
  }, [children, vaccines, growthRecords]);

  const onRefresh = async () => {
    setRefreshing(true);
    await fetchData();
    setRefreshing(false);
  };

  const handleChildSwitch = async (child: any) => {
    setSelectedChild(child);
    await fetchChildDetails(child.child_id);
  };

  if (authLoading || loading) {
    return <ActivityIndicator style={{ flex: 1 }} />;
  }

  if (error) {
    return <View style={styles.center}><Text style={styles.error}>{error}</Text></View>;
  }

  return (
    <ScrollView style={{ flex: 1, backgroundColor: COLORS.background }} contentContainerStyle={{ paddingBottom: 32 }}>
      <View style={styles.header}>
        <View style={styles.avatarCircle}>
          <Ionicons name="person-circle" size={54} color={COLORS.primary} />
        </View>
        <View>
          <Text style={styles.greeting}>Welcome,</Text>
          <Text style={styles.username}>{user?.fullname || user?.username}</Text>
        </View>
      </View>
      <View style={styles.statsRow}>
        <View style={[styles.statCard, { backgroundColor: '#eaf1fb' }]}> 
          <FontAwesome5 name="child" size={24} color={COLORS.primary} />
          <Text style={styles.statCount}>{totalChildren}</Text>
          <Text style={styles.statLabel}>Children</Text>
        </View>
        <View style={[styles.statCard, { backgroundColor: '#eafbe7' }]}> 
          <MaterialCommunityIcons name="needle" size={24} color={COLORS.secondary} />
          <Text style={styles.statCount}>{totalVaccinations}</Text>
          <Text style={styles.statLabel}>Vaccinations</Text>
        </View>
        <View style={[styles.statCard, { backgroundColor: '#fff6e5' }]}> 
          <FontAwesome5 name="chart-line" size={24} color={COLORS.accent} />
          <Text style={styles.statCount}>{totalGrowthRecords}</Text>
          <Text style={styles.statLabel}>Growth</Text>
        </View>
      </View>
      {children.length > 1 && (
        <ScrollView horizontal showsHorizontalScrollIndicator={false} style={{ marginVertical: 12 }}>
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
          <Text style={styles.sectionTitle}>Growth Records</Text>
          {growthRecords.length === 0 ? (
            <Text style={styles.emptyText}>No growth records found.</Text>
          ) : (
            <FlatList
              data={growthRecords}
              keyExtractor={item => item.id.toString()}
              renderItem={({ item }) => (
                <View style={styles.recordCard}>
                  <FontAwesome5 name="ruler-vertical" size={16} color={COLORS.accent} style={{ marginRight: 8 }} />
                  <Text style={styles.recordText}>{item.date_recorded}: {item.height}cm, {item.weight}kg</Text>
                </View>
              )}
              scrollEnabled={false}
            />
          )}
          <Text style={styles.sectionTitle}>Vaccination Records</Text>
          {vaccines.length === 0 ? (
            <Text style={styles.emptyText}>No vaccination records found.</Text>
          ) : (
            <FlatList
              data={vaccines}
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
  header: { flexDirection: 'row', alignItems: 'center', padding: 20, backgroundColor: COLORS.card, borderRadius: 16, margin: 16, marginBottom: 0, shadowColor: '#000', shadowOpacity: 0.04, shadowRadius: 8, elevation: 2 },
  avatarCircle: { width: 54, height: 54, borderRadius: 27, backgroundColor: '#eaf1fb', alignItems: 'center', justifyContent: 'center', marginRight: 16 },
  greeting: { fontSize: 16, color: COLORS.gray },
  username: { fontSize: 20, fontWeight: 'bold', color: COLORS.primary },
  statsRow: { flexDirection: 'row', justifyContent: 'space-between', margin: 16 },
  statCard: { flex: 1, alignItems: 'center', marginHorizontal: 4, borderRadius: 12, padding: 16, backgroundColor: COLORS.card, shadowColor: '#000', shadowOpacity: 0.03, shadowRadius: 4, elevation: 1 },
  statCount: { fontSize: 22, fontWeight: 'bold', color: COLORS.text, marginTop: 4 },
  statLabel: { fontSize: 13, color: COLORS.gray },
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

export default HomeTab; 