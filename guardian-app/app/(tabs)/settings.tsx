import React, { useEffect, useState } from 'react';
import { View, Text, TextInput, ActivityIndicator, StyleSheet, TouchableOpacity, Alert } from 'react-native';
import { Ionicons, MaterialIcons } from '@expo/vector-icons';
import { useAuth } from '../AuthContext';
import guardianService from '../../services/guardianService';

const COLORS = {
  primary: '#2a5ca4',
  background: '#f5f7fa',
  card: '#fff',
  text: '#222',
  gray: '#888',
  error: 'red',
  success: 'green',
};

const SettingsTab = () => {
  const { user, logout, refreshProfile } = useAuth();
  const [profile, setProfile] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [oldPassword, setOldPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [changing, setChanging] = useState(false);
  const [success, setSuccess] = useState('');

  useEffect(() => {
    const fetchProfile = async () => {
      setLoading(true);
      setError('');
      try {
        const data = await guardianService.getProfile();
        setProfile(data);
      } catch (err: any) {
        setError(err.detail || 'Failed to load profile');
      }
      setLoading(false);
    };
    fetchProfile();
  }, [refreshProfile]);

  const handleChangePassword = async () => {
    setSuccess('');
    setError('');
    if (!oldPassword || !newPassword || !confirmPassword) {
      setError('Please fill all password fields.');
      return;
    }
    if (newPassword !== confirmPassword) {
      setError('New passwords do not match.');
      return;
    }
    setChanging(true);
    try {
      await guardianService.changePassword({ old_password: oldPassword, new_password: newPassword });
      setSuccess('Password changed successfully.');
      setOldPassword('');
      setNewPassword('');
      setConfirmPassword('');
    } catch (err: any) {
      setError(err.detail || 'Password change failed');
    }
    setChanging(false);
  };

  if (loading) {
    return <ActivityIndicator style={{ flex: 1 }} />;
  }

  if (error) {
    return <View style={styles.center}><Text style={styles.error}>{error}</Text></View>;
  }

  return (
    <View style={{ flex: 1, backgroundColor: COLORS.background, padding: 20 }}>
      <View style={styles.profileCard}>
        <Ionicons name="person-circle" size={48} color={COLORS.primary} style={{ alignSelf: 'center', marginBottom: 8 }} />
        <Text style={styles.title}>Profile</Text>
        {profile && (
          <View style={styles.profileBox}>
            <Text style={styles.profileText}>Full Name: {profile.fullname}</Text>
            <Text style={styles.profileText}>Email: {profile.email}</Text>
            <Text style={styles.profileText}>Phone: {profile.phone_number}</Text>
            <Text style={styles.profileText}>National ID: {profile.national_id}</Text>
          </View>
        )}
      </View>
      <View style={styles.card}>
        <Text style={styles.title}>Change Password</Text>
        <View style={styles.inputRow}>
          <MaterialIcons name="lock-outline" size={20} color={COLORS.gray} style={{ marginRight: 8 }} />
          <TextInput
            style={styles.input}
            placeholder="Current Password"
            value={oldPassword}
            onChangeText={setOldPassword}
            secureTextEntry
          />
        </View>
        <View style={styles.inputRow}>
          <MaterialIcons name="lock-reset" size={20} color={COLORS.gray} style={{ marginRight: 8 }} />
          <TextInput
            style={styles.input}
            placeholder="New Password"
            value={newPassword}
            onChangeText={setNewPassword}
            secureTextEntry
          />
        </View>
        <View style={styles.inputRow}>
          <MaterialIcons name="lock-reset" size={20} color={COLORS.gray} style={{ marginRight: 8 }} />
          <TextInput
            style={styles.input}
            placeholder="Confirm New Password"
            value={confirmPassword}
            onChangeText={setConfirmPassword}
            secureTextEntry
          />
        </View>
        {changing ? <ActivityIndicator /> : (
          <TouchableOpacity style={styles.primaryBtn} onPress={handleChangePassword}>
            <Text style={styles.primaryBtnText}>Change Password</Text>
          </TouchableOpacity>
        )}
        {success ? <Text style={styles.success}>{success}</Text> : null}
      </View>
      <TouchableOpacity style={styles.logoutBtn} onPress={logout}>
        <Ionicons name="log-out-outline" size={20} color="#fff" />
        <Text style={styles.logoutBtnText}>Logout</Text>
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  profileCard: { backgroundColor: COLORS.card, borderRadius: 16, padding: 20, marginBottom: 20, shadowColor: '#000', shadowOpacity: 0.04, shadowRadius: 8, elevation: 2 },
  profileBox: { marginBottom: 10 },
  profileText: { fontSize: 15, color: COLORS.text, marginBottom: 2 },
  title: { fontSize: 18, fontWeight: 'bold', color: COLORS.primary, marginBottom: 8, textAlign: 'center' },
  card: { backgroundColor: COLORS.card, borderRadius: 16, padding: 20, marginBottom: 20, shadowColor: '#000', shadowOpacity: 0.04, shadowRadius: 8, elevation: 2 },
  inputRow: { flexDirection: 'row', alignItems: 'center', marginBottom: 10 },
  input: { flex: 1, borderWidth: 1, borderColor: '#ccc', borderRadius: 8, padding: 10, backgroundColor: '#f5f7fa' },
  error: { color: COLORS.error, marginBottom: 10, textAlign: 'center' },
  success: { color: COLORS.success, marginBottom: 10, textAlign: 'center' },
  primaryBtn: { backgroundColor: COLORS.primary, borderRadius: 24, paddingVertical: 12, alignItems: 'center', marginTop: 8 },
  primaryBtnText: { color: '#fff', fontWeight: 'bold', fontSize: 16 },
  logoutBtn: { flexDirection: 'row', alignItems: 'center', justifyContent: 'center', backgroundColor: COLORS.error, borderRadius: 24, paddingVertical: 12, marginTop: 8 },
  logoutBtnText: { color: '#fff', fontWeight: 'bold', marginLeft: 8, fontSize: 16 },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center' },
});

export default SettingsTab; 