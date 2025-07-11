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

export default function SystemAdminDashboard() {
  const [stats, setStats] = useState({
    totalUsers: 0,
    totalFacilities: 0,
    totalChildren: 0,
    totalRecords: 0,
  });

  useEffect(() => {
    loadDashboardStats();
  }, []);

  const loadDashboardStats = async () => {
    // Mock data - replace with actual API calls
    setStats({
      totalUsers: 156,
      totalFacilities: 12,
      totalChildren: 234,
      totalRecords: 1247,
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
      case 'users':
        // router.push('/system-admin/users');
        Alert.alert('Coming Soon', 'User management feature will be available soon');
        break;
      case 'facilities':
        // router.push('/system-admin/facilities');
        Alert.alert('Coming Soon', 'Facility management feature will be available soon');
        break;
      case 'records':
        // router.push('/system-admin/all-records');
        Alert.alert('Coming Soon', 'All records feature will be available soon');
        break;
      case 'system':
        // router.push('/system-admin/system-settings');
        Alert.alert('Coming Soon', 'System settings feature will be available soon');
        break;
      case 'reports':
        // router.push('/system-admin/reports');
        Alert.alert('Coming Soon', 'Reports feature will be available soon');
        break;
      case 'backup':
        // router.push('/system-admin/backup-restore');
        Alert.alert('Coming Soon', 'Backup & restore feature will be available soon');
        break;
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView style={styles.scrollView}>
        {/* Header */}
        <View style={styles.header}>
          <View>
            <Text style={styles.greeting}>Welcome, System Administrator</Text>
            <Text style={styles.subtitle}>Full system access and management</Text>
          </View>
          <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
            <Ionicons name="log-out-outline" size={24} color={COLORS.danger} />
          </TouchableOpacity>
        </View>

        {/* Stats Cards */}
        <View style={styles.statsContainer}>
          <View style={styles.statCard}>
            <Ionicons name="people" size={32} color={COLORS.primary} />
            <Text style={styles.statNumber}>{stats.totalUsers}</Text>
            <Text style={styles.statLabel}>Total Users</Text>
          </View>
          
          <View style={styles.statCard}>
            <Ionicons name="business" size={32} color={COLORS.success} />
            <Text style={styles.statNumber}>{stats.totalFacilities}</Text>
            <Text style={styles.statLabel}>Health Facilities</Text>
          </View>
          
          <View style={styles.statCard}>
            <Ionicons name="people" size={32} color={COLORS.warning} />
            <Text style={styles.statNumber}>{stats.totalChildren}</Text>
            <Text style={styles.statLabel}>Children</Text>
          </View>
          
          <View style={styles.statCard}>
            <Ionicons name="document-text" size={32} color={COLORS.secondary} />
            <Text style={styles.statNumber}>{stats.totalRecords}</Text>
            <Text style={styles.statLabel}>Total Records</Text>
          </View>
        </View>

        {/* System Management */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>System Management</Text>
          
          <View style={styles.actionGrid}>
            <TouchableOpacity 
              style={styles.actionCard} 
              onPress={() => navigateToSection('users')}
            >
              <Ionicons name="people" size={32} color={COLORS.primary} />
              <Text style={styles.actionTitle}>User Management</Text>
              <Text style={styles.actionSubtitle}>Manage all user accounts</Text>
            </TouchableOpacity>
            
            <TouchableOpacity 
              style={styles.actionCard} 
              onPress={() => navigateToSection('facilities')}
            >
              <Ionicons name="business" size={32} color={COLORS.success} />
              <Text style={styles.actionTitle}>Facility Management</Text>
              <Text style={styles.actionSubtitle}>Manage health facilities</Text>
            </TouchableOpacity>
            
            <TouchableOpacity 
              style={styles.actionCard} 
              onPress={() => navigateToSection('records')}
            >
              <Ionicons name="document-text" size={32} color={COLORS.warning} />
              <Text style={styles.actionTitle}>All Records</Text>
              <Text style={styles.actionSubtitle}>Access all system records</Text>
            </TouchableOpacity>
            
            <TouchableOpacity 
              style={styles.actionCard} 
              onPress={() => navigateToSection('system')}
            >
              <Ionicons name="settings" size={32} color={COLORS.secondary} />
              <Text style={styles.actionTitle}>System Settings</Text>
              <Text style={styles.actionSubtitle}>Configure system parameters</Text>
            </TouchableOpacity>
          </View>
        </View>

        {/* Advanced Features */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Advanced Features</Text>
          
          <View style={styles.actionGrid}>
            <TouchableOpacity 
              style={styles.actionCard} 
              onPress={() => navigateToSection('reports')}
            >
              <Ionicons name="analytics" size={32} color={COLORS.primary} />
              <Text style={styles.actionTitle}>System Reports</Text>
              <Text style={styles.actionSubtitle}>Generate comprehensive reports</Text>
            </TouchableOpacity>
            
            <TouchableOpacity 
              style={styles.actionCard} 
              onPress={() => navigateToSection('backup')}
            >
              <Ionicons name="cloud-upload" size={32} color={COLORS.success} />
              <Text style={styles.actionTitle}>Backup & Restore</Text>
              <Text style={styles.actionSubtitle}>Manage system backups</Text>
            </TouchableOpacity>
          </View>
        </View>

        {/* System Status */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>System Status</Text>
          
          <View style={styles.statusContainer}>
            <View style={styles.statusCard}>
              <Ionicons name="checkmark-circle" size={24} color={COLORS.success} />
              <View style={styles.statusContent}>
                <Text style={styles.statusTitle}>System Online</Text>
                <Text style={styles.statusSubtitle}>All services operational</Text>
              </View>
            </View>
            
            <View style={styles.statusCard}>
              <Ionicons name="shield-checkmark" size={24} color={COLORS.success} />
              <View style={styles.statusContent}>
                <Text style={styles.statusTitle}>Security Active</Text>
                <Text style={styles.statusSubtitle}>All security measures enabled</Text>
              </View>
            </View>
            
            <View style={styles.statusCard}>
              <Ionicons name="cloud-done" size={24} color={COLORS.success} />
              <View style={styles.statusContent}>
                <Text style={styles.statusTitle}>Backup Current</Text>
                <Text style={styles.statusSubtitle}>Last backup: 2 hours ago</Text>
              </View>
            </View>
          </View>
        </View>

        {/* Recent Activity */}
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>System Activity</Text>
          
          <View style={styles.activityList}>
            <View style={styles.activityItem}>
              <Ionicons name="person-add" size={20} color={COLORS.primary} />
              <View style={styles.activityContent}>
                <Text style={styles.activityTitle}>New facility admin account created</Text>
                <Text style={styles.activityTime}>30 minutes ago</Text>
              </View>
            </View>
            
            <View style={styles.activityItem}>
              <Ionicons name="cloud-upload" size={20} color={COLORS.success} />
              <View style={styles.activityContent}>
                <Text style={styles.activityTitle}>System backup completed</Text>
                <Text style={styles.activityTime}>2 hours ago</Text>
              </View>
            </View>
            
            <View style={styles.activityItem}>
              <Ionicons name="analytics" size={20} color={COLORS.warning} />
              <View style={styles.activityContent}>
                <Text style={styles.activityTitle}>Monthly system report generated</Text>
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
  statusContainer: {
    backgroundColor: COLORS.card,
    borderRadius: 12,
    padding: 16,
    shadowColor: '#000',
    shadowOpacity: 0.04,
    shadowRadius: 8,
    elevation: 2,
  },
  statusCard: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  statusContent: {
    flex: 1,
    marginLeft: 12,
  },
  statusTitle: {
    fontSize: 14,
    color: COLORS.text,
    fontWeight: '500',
  },
  statusSubtitle: {
    fontSize: 12,
    color: COLORS.gray,
    marginTop: 2,
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