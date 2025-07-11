import React, { useEffect, useState, useCallback } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView } from 'react-native';
import { Calendar, DateData } from 'react-native-calendars';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import guardianService from '../../services/guardianService';
import { useAuth } from '../AuthContext';

const COLORS = {
  primary: '#2a5ca4',
  background: '#f5f7fa',
  card: '#fff',
  text: '#222',
  gray: '#888',
};

export default function CalendarScreen() {
  const { user } = useAuth();
  const [children, setChildren] = useState<any[]>([]);
  const [selectedChild, setSelectedChild] = useState<any>(null);
  const [markedDates, setMarkedDates] = useState<{ [date: string]: any }>({});
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string>('');
  const [refreshing, setRefreshing] = useState(false);

  const fetchChildren = useCallback(async () => {
    setLoading(true);
    setError('');
    try {
      const data = await guardianService.getChildren();
      setChildren(data);
      if (data.length > 0) setSelectedChild(data[0]);
    } catch (err) {
      setError('Failed to load children.');
    } finally {
      setLoading(false);
    }
  }, []);

  const fetchVaccinationRecords = useCallback(async (childId: any) => {
    setLoading(true);
    setError('');
    try {
      const records = await guardianService.getChildVaccinationRecords(childId);
      console.log('Calendar: Vaccination records received:', records);
      const dates: { [date: string]: any } = {};
      records.forEach((rec: any) => {
        if (rec.administration_date) {
          // Extract just the date part (YYYY-MM-DD) from the datetime
          const dateOnly = rec.administration_date.split(' ')[0];
          dates[dateOnly] = { 
            selected: true, 
            selectedColor: '#00C853',
            selectedTextColor: '#fff'
          };
          console.log('Calendar: Marking date:', dateOnly);
        }
      });
      console.log('Calendar: Final marked dates:', dates);
      setMarkedDates(dates);
    } catch (err) {
      console.log('Calendar: Error fetching vaccination records:', err);
      setError('Failed to load vaccination records.');
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    fetchChildren();
  }, [fetchChildren]);

  useEffect(() => {
    if (selectedChild) {
      fetchVaccinationRecords(selectedChild.child_id || selectedChild.id);
    }
  }, [selectedChild, fetchVaccinationRecords]);

  const onRefresh = async () => {
    setRefreshing(true);
    await fetchChildren();
    if (selectedChild) await fetchVaccinationRecords(selectedChild.child_id || selectedChild.id);
    setRefreshing(false);
  };

  return (
    <SafeAreaView style={{ flex: 1, backgroundColor: COLORS.background }}>
      <ScrollView contentContainerStyle={{ paddingBottom: 32 }}>
        <Text style={styles.title}>Vaccination Calendar</Text>
        {children.length > 1 && (
          <ScrollView horizontal showsHorizontalScrollIndicator={false} style={{ marginVertical: 12, marginLeft: 8 }}>
            {children.map(child => (
              <TouchableOpacity
                key={child.child_id || child.id}
                style={[styles.childPill, selectedChild?.child_id === child.child_id && styles.childPillSelected]}
                onPress={() => setSelectedChild(child)}
              >
                <Ionicons name="person" size={18} color={selectedChild?.child_id === child.child_id ? '#fff' : COLORS.primary} />
                <Text style={[styles.childPillText, selectedChild?.child_id === child.child_id && { color: '#fff' }]}>{child.first_name}</Text>
              </TouchableOpacity>
            ))}
          </ScrollView>
        )}
        <View style={styles.card}>
          <Calendar
            markedDates={markedDates}
            onDayPress={(day: DateData) => {
              // Optionally show details for the selected day
            }}
            theme={{
              selectedDayBackgroundColor: COLORS.primary,
              todayTextColor: COLORS.primary,
              arrowColor: COLORS.primary,
              textSectionTitleColor: COLORS.primary,
              monthTextColor: COLORS.primary,
              textDayFontWeight: '500',
              textMonthFontWeight: 'bold',
              textDayHeaderFontWeight: 'bold',
            }}
            style={{ borderRadius: 16 }}
          />
        </View>
        <TouchableOpacity style={styles.refreshBtn} onPress={onRefresh}>
          <Ionicons name="refresh" size={20} color="#fff" />
          <Text style={styles.refreshBtnText}>Refresh</Text>
        </TouchableOpacity>
        {error && <Text style={styles.error}>{error}</Text>}
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  title: { fontSize: 20, fontWeight: 'bold', color: COLORS.primary, margin: 20, marginBottom: 8, textAlign: 'center' },
  card: { backgroundColor: COLORS.card, borderRadius: 16, padding: 16, margin: 16, shadowColor: '#000', shadowOpacity: 0.04, shadowRadius: 8, elevation: 2 },
  childPill: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#eaf1fb', borderRadius: 20, paddingHorizontal: 16, paddingVertical: 8, marginRight: 8 },
  childPillSelected: { backgroundColor: COLORS.primary },
  childPillText: { marginLeft: 6, color: COLORS.primary, fontWeight: 'bold' },
  refreshBtn: { flexDirection: 'row', alignItems: 'center', justifyContent: 'center', backgroundColor: COLORS.primary, borderRadius: 24, paddingVertical: 12, margin: 24 },
  refreshBtnText: { color: '#fff', fontWeight: 'bold', marginLeft: 8, fontSize: 16 },
  error: { color: 'red', marginBottom: 10, textAlign: 'center' },
});
