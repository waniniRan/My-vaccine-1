import React, { useEffect, useState, useCallback } from 'react';
import { View, Text, StyleSheet, ScrollView, TouchableOpacity } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { SafeAreaView } from 'react-native-safe-area-context';
import guardianService from '../../services/guardianService';
import { useAuth } from '../AuthContext';

const COLORS = {
  primary: '#2a5ca4',
  background: '#f5f7fa',
  card: '#fff',
  text: '#222',
  gray: '#888',
};

interface Notification {
  id: number;
  guardian_id: number;
  notification_type: string;
  message: string;
  is_sent: number;
  date_sent: string | null;
  date_created: string;
}

const NotificationsTab = () => {
  const { user } = useAuth();
  const [notifications, setNotifications] = useState<Notification[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [refreshing, setRefreshing] = useState(false);

  const fetchNotifications = useCallback(async () => {
    setLoading(true);
    setError(null);
    try {
      const data = await guardianService.getNotifications();
      setNotifications(data);
    } catch (err) {
      setError('Failed to load notifications.');
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    fetchNotifications();
  }, [fetchNotifications]);

  const onRefresh = async () => {
    setRefreshing(true);
    await fetchNotifications();
    setRefreshing(false);
  };

  return (
    <SafeAreaView style={{ flex: 1, backgroundColor: COLORS.background }}>
      <ScrollView style={styles.container} contentContainerStyle={{ paddingBottom: 32 }}>
        <Text style={styles.headerTitle}>Notifications</Text>
        <TouchableOpacity style={styles.refreshBtn} onPress={onRefresh}>
          <Ionicons name="refresh" size={20} color="#fff" />
          <Text style={styles.refreshBtnText}>Refresh</Text>
        </TouchableOpacity>
        {notifications.length === 0 ? (
          <Text style={styles.noNotifications}>No notifications found.</Text>
        ) : (
          notifications.map((notif) => (
            <View key={notif.id} style={styles.notificationCard}>
              <View style={styles.cardHeader}>
                <Ionicons
                  name="notifications-outline"
                  size={20}
                  color={COLORS.primary}
                  style={{ marginRight: 6 }}
                />
                <Text style={styles.notifType}>{notif.notification_type}</Text>
              </View>
              <Text style={styles.notifMessage}>{notif.message}</Text>
              <Text style={styles.notifDate}>
                {new Date(notif.date_created).toLocaleDateString()}
              </Text>
            </View>
          ))
        )}
        {error && <Text style={styles.error}>{error}</Text>}
      </ScrollView>
    </SafeAreaView>
  );
};

export default NotificationsTab;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 16,
    backgroundColor: COLORS.background,
  },
  headerTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 16,
    color: COLORS.primary,
    textAlign: 'center',
  },
  notificationCard: {
    backgroundColor: COLORS.card,
    borderRadius: 12,
    padding: 16,
    marginBottom: 14,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.08,
    shadowRadius: 3.84,
    elevation: 2,
    borderColor: '#e0e0e0',
  },
  cardHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 8,
  },
  notifType: {
    fontWeight: 'bold',
    fontSize: 14,
    color: COLORS.text,
  },
  notifMessage: {
    fontSize: 14,
    color: COLORS.text,
    marginBottom: 4,
  },
  notifDate: {
    fontSize: 12,
    color: COLORS.gray,
  },
  noNotifications: {
    textAlign: 'center',
    color: COLORS.gray,
    fontSize: 16,
    marginTop: 20,
  },
  refreshBtn: { flexDirection: 'row', alignItems: 'center', justifyContent: 'center', backgroundColor: COLORS.primary, borderRadius: 24, paddingVertical: 12, marginVertical: 12 },
  refreshBtnText: { color: '#fff', fontWeight: 'bold', marginLeft: 8, fontSize: 16 },
  error: { color: 'red', marginBottom: 10, textAlign: 'center' },
});
