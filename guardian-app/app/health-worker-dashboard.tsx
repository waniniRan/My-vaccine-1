import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView, Alert } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { router } from 'expo-router';

const COLORS = {
  primary: '#2a5ca4',
  secondary: '#34C759',
  background: '#f5f7fa',
  card: '#fff',
  text: '#222',
  gray: '#888',
  success: '#34C759',
  warning: '#FF9500',
  danger: '#FF3B30',
};

export default function HealthWorkerDashboard() {
  const [stats, setStats] = useState({
    totalChildren: 0,
    pendingVaccinations: 0,
    growthRecords: 0,
    guardians: 0,
  });

  useEffect(() => {
    // Load dashboard stats
    loadDashboardStats();
  }, []);

  const loadDashboardStats = async () => {
    // Mock data - replace with actual API calls
    setStats({
      totalChildren: 45,
      pendingVaccinations: 12,
      growthRecords: 156,
      guardians: 38,
    });
  };

  const handleLogout = () => {
    Alert.alert(
      'Logout',
      'Are you sure you want to logout?',
      [
        { text: 'Cancel', style: 'cancel' },
        { text: 'Logout', style: 'destructive', onPress: () => router.replace('/welcome') },
      ]
    );
  };

  const navigateToSection = (section: string) => {
    switch (section) {
      case 'growth':
        router.push('/health-worker/growth-records');
        break;
      case 'vaccinations':
        router.push('/health-worker/vaccination-records');
        break;
      case 'guardians':
        router.push('/health-worker/guardian-accounts');
        break;
      case 'children':
        router.push('/health-worker/children');
        break;
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView style={styles.scrollView}>
        {/* Header */}
        <View style={styles.header}>
          <View>
            <Text style={styles.greeting}>Welcome, Health Worker</Text>
            <Text style={styles.subtitle}>Manage child health records and vaccinations</Text>
          </View>
          <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
            <Ionicons name="log-out-outline" size={24} color={COLORS.danger} />
          </TouchableOpacity>
        </View>

        {/* Stats Cards */}
        <View style={styles.statsContainer}>
          <View style={styles.statCard}>
            <Ionicons name="people" size={32} color={COLORS.primary} />
            <Text style={styles.statNumber}>{stats.totalChildren}</Text>
            <Text style={styles.statLabel}>Total Children</Text>
          </View>
          
          <View style={styles.statCard}>
            <Ionicons name="medical" size={32} color={COLORS.warning} />
            <Text style={styles.statNumber}>{stats.pendingVaccinations}</Text>
            <Text style={styles.statLabel}>Pending Vaccinations</Text>
          </View>
          
          <View style={styles.statCard}>
            <Ionicons name="trending-up" size={32} color={COLORS.success} />
            <Text style={styles.statNumber}>{stats.growthRecords}</Text>
            <Text style={styles.statLabel}>Growth Records</Text>
          </View>
          
          <View style={styles.statCard}>
            <Ionicons name="person" size={32} color={COLORS.secondary} />
            <Text style={styles.statNumber}>{stats.guardians}</Text>
            <Text style={styles.statLabel}>Guardians</Text>
          </View>
        </View>

        {/* Quick Actions */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Quick Actions</Text>
          
          <View style={styles.actionGrid}>
            <TouchableOpacity 
              style={styles.actionCard} 
              onPress={() => navigateToSection('growth')}
            >
              <Ionicons name="add-circle" size={32} color={COLORS.primary} />
              <Text style={styles.actionTitle}>Add Growth Record</Text>
              <Text style={styles.actionSubtitle}>Record child growth measurements</Text>
            </TouchableOpacity>
            
            <TouchableOpacity 
              style={styles.actionCard} 
              onPress={() => navigateToSection('vaccinations')}
            >
              <Ionicons name="add-circle" size={32} color={COLORS.success} />
              <Text style={styles.actionTitle}>Add Vaccination</Text>
              <Text style={styles.actionSubtitle}>Record vaccination administration</Text>
            </TouchableOpacity>
            
            <TouchableOpacity 
              style={styles.actionCard} 
              onPress={() => navigateToSection('guardians')}
            >
              <Ionicons name="person-add" size={32} color={COLORS.secondary} />
              <Text style={styles.actionTitle}>Manage Guardians</Text>
              <Text style={styles.actionSubtitle}>Add or update guardian accounts</Text>
            </TouchableOpacity>
            
            <TouchableOpacity 
              style={styles.actionCard} 
              onPress={() => navigateToSection('children')}
            >
              <Ionicons name="people" size={32} color={COLORS.warning} />
              <Text style={styles.actionTitle}>Manage Children</Text>
              <Text style={styles.actionSubtitle}>View and update child records</Text>
            </TouchableOpacity>
          </View>
        </View>

        {/* Recent Activity */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Recent Activity</Text>
          
          <View style={styles.activityList}>
            <View style={styles.activityItem}>
              <Ionicons name="trending-up" size={20} color={COLORS.success} />
              <View style={styles.activityContent}>
                <Text style={styles.activityTitle}>Growth record added for Alice</Text>
                <Text style={styles.activityTime}>2 hours ago</Text>
              </View>
            </View>
            
            <View style={styles.activityItem}>
              <Ionicons name="medical" size={20} color={COLORS.primary} />
              <View style={styles.activityContent}>
                <Text style={styles.activityTitle}>Vaccination administered to Bob</Text>
                <Text style={styles.activityTime}>4 hours ago</Text>
              </View>
            </View>
            
            <View style={styles.activityItem}>
              <Ionicons name="person-add" size={20} color={COLORS.secondary} />
              <View style={styles.activityContent}>
                <Text style={styles.activityTitle}>New guardian account created</Text>
                <Text style={styles.activityTime}>1 day ago</Text>
              </View>
            </View>
          </View>
        </View>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: COLORS.background,
  },
  scrollView: {
    flex: 1,
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 20,
    paddingBottom: 10,
  },
  greeting: {
    fontSize: 24,
    fontWeight: 'bold',
    color: COLORS.text,
  },
  subtitle: {
    fontSize: 14,
    color: COLORS.gray,
    marginTop: 4,
  },
  logoutButton: {
    padding: 8,
  },
  statsContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    padding: 20,
    paddingTop: 10,
  },
  statCard: {
    backgroundColor: COLORS.card,
    borderRadius: 12,
    padding: 16,
    marginBottom: 12,
    width: '48%',
    alignItems: 'center',
    shadowColor: '#000',
    shadowOpacity: 0.04,
    shadowRadius: 8,
    elevation: 2,
  },
  statNumber: {
    fontSize: 24,
    fontWeight: 'bold',
    color: COLORS.text,
    marginTop: 8,
  },
  statLabel: {
    fontSize: 12,
    color: COLORS.gray,
    textAlign: 'center',
    marginTop: 4,
  },
  section: {
    padding: 20,
    paddingTop: 0,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: COLORS.text,
    marginBottom: 16,
  },
  actionGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
  },
  actionCard: {
    backgroundColor: COLORS.card,
    borderRadius: 12,
    padding: 16,
    marginBottom: 12,
    width: '48%',
    alignItems: 'center',
    shadowColor: '#000',
    shadowOpacity: 0.04,
    shadowRadius: 8,
    elevation: 2,
  },
  actionTitle: {
    fontSize: 14,
    fontWeight: 'bold',
    color: COLORS.text,
    marginTop: 8,
    textAlign: 'center',
  },
  actionSubtitle: {
    fontSize: 11,
    color: COLORS.gray,
    textAlign: 'center',
    marginTop: 4,
  },
  activityList: {
    backgroundColor: COLORS.card,
    borderRadius: 12,
    padding: 16,
    shadowColor: '#000',
    shadowOpacity: 0.04,
    shadowRadius: 8,
    elevation: 2,
  },
  activityItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  activityContent: {
    flex: 1,
    marginLeft: 12,
  },
  activityTitle: {
    fontSize: 14,
    color: COLORS.text,
    fontWeight: '500',
  },
  activityTime: {
    fontSize: 12,
    color: COLORS.gray,
    marginTop: 2,
  },
});