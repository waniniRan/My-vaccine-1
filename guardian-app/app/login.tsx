import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet, Alert, ScrollView, ActivityIndicator } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { router } from 'expo-router';
import { Picker } from '@react-native-picker/picker';
import { useAuth } from './AuthContext';
import guardianService from '../services/guardianService';
import AsyncStorage from '@react-native-async-storage/async-storage';

interface LoginResponse {
  status: string;
  user_id?: number;
  role?: string;
  message?: string;
}

const COLORS = {
  primary: '#2a5ca4',
  secondary: '#34C759',
  background: '#f5f7fa',
  card: '#fff',
  text: '#222',
  gray: '#888',
  error: '#ff4444',
};

const ROLES = [
  { label: 'Select Role', value: '' },
  { label: 'Parent/Guardian', value: 'GUARDIAN' },
  { label: 'Health Worker', value: 'WORKER' },
  { label: 'Facility Admin', value: 'FACILITY_ADMIN' },
  { label: 'System Admin', value: 'SYSTEM_ADMIN' },
];

export default function LoginScreen() {
  const { login } = useAuth();
  const [idNumber, setIdNumber] = useState('');
  const [password, setPassword] = useState('');
  const [selectedRole, setSelectedRole] = useState('');
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [error, setError] = useState('');

  const handleLogin = async () => {
    if (!idNumber || !password || !selectedRole) {
      setError('Please fill in all fields');
      return;
    }

    setLoading(true);
    setError('');
    
    try {
      const response = await guardianService.login({ id_number: idNumber, password, role: selectedRole }) as LoginResponse;
      
      if (response.status === 'success') {
        if (response.user_id) {
          await AsyncStorage.setItem('user_id', response.user_id.toString());
        }
        await AsyncStorage.setItem('user_role', selectedRole);
        await AsyncStorage.setItem('user_id_number', idNumber);
        
        const token = btoa(`${response.user_id}:${selectedRole}`);
        await AsyncStorage.setItem('accessToken', token);
        await login({ access: token, refresh: token });
        
        switch (selectedRole) {
          case 'GUARDIAN':
            router.replace('/(tabs)');
            break;
          case 'WORKER':
            router.replace('/health-worker-dashboard');
            break;
          case 'FACILITY_ADMIN':
            router.replace('/facility-admin-dashboard');
            break;
          case 'SYSTEM_ADMIN':
            router.replace('/system-admin-dashboard');
            break;
          default:
            setError('Invalid role selected');
        }
      } else {
        setError(response.message || 'Login failed.');
      }
    } catch (err: any) {
      setError('Login failed: ' + (err.message || 'Unknown error'));
    } finally {
      setLoading(false);
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView contentContainerStyle={styles.scrollContent}>
        <View style={styles.header}>
          <TouchableOpacity style={styles.backButton} onPress={() => router.back()}>
            <Ionicons name="arrow-back" size={24} color={COLORS.primary} />
          </TouchableOpacity>
          <Text style={styles.title}>Welcome Back</Text>
          <Text style={styles.subtitle}>Sign in to your account</Text>
        </View>

        <View style={styles.formContainer}>
          <View style={styles.inputContainer}>
            <Ionicons name="id-card" size={20} color={COLORS.gray} style={styles.inputIcon} />
            <TextInput
              style={styles.input}
              placeholder="ID Number"
              value={idNumber}
              onChangeText={setIdNumber}
              keyboardType="numeric"
              autoCapitalize="none"
            />
          </View>

          <View style={styles.inputContainer}>
            <Ionicons name="lock-closed" size={20} color={COLORS.gray} style={styles.inputIcon} />
            <TextInput
              style={styles.input}
              placeholder="Password"
              value={password}
              onChangeText={setPassword}
              secureTextEntry={!showPassword}
              autoCapitalize="none"
            />
            <TouchableOpacity 
              style={styles.eyeButton} 
              onPress={() => setShowPassword(!showPassword)}
            >
              <Ionicons 
                name={showPassword ? "eye-off" : "eye"} 
                size={20} 
                color={COLORS.gray} 
              />
            </TouchableOpacity>
          </View>

          <View style={styles.pickerContainer}>
            <Ionicons name="people" size={20} color={COLORS.gray} style={styles.inputIcon} />
            <Picker
              selectedValue={selectedRole}
              onValueChange={setSelectedRole}
              style={styles.picker}
            >
              {ROLES.map((role) => (
                <Picker.Item 
                  key={role.value} 
                  label={role.label} 
                  value={role.value} 
                />
              ))}
            </Picker>
          </View>

          {error ? <Text style={styles.error}>{error}</Text> : null}

          <TouchableOpacity 
            style={[styles.loginButton, loading && styles.loginButtonDisabled]} 
            onPress={handleLogin}
            disabled={loading}
          >
            {loading ? (
              <ActivityIndicator color="#fff" />
            ) : (
              <>
                <Ionicons name="log-in" size={20} color="#fff" />
                <Text style={styles.loginButtonText}>Sign In</Text>
              </>
            )}
          </TouchableOpacity>

          <TouchableOpacity style={styles.forgotPassword}>
            <Text style={styles.forgotPasswordText}>Forgot Password?</Text>
          </TouchableOpacity>
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
  scrollContent: {
    flexGrow: 1,
    padding: 20,
  },
  header: {
    marginTop: 40,
    marginBottom: 40,
  },
  backButton: {
    marginBottom: 20,
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: COLORS.text,
    marginBottom: 8,
  },
  subtitle: {
    fontSize: 16,
    color: COLORS.gray,
  },
  formContainer: {
    flex: 1,
  },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: COLORS.card,
    borderRadius: 12,
    marginBottom: 16,
    paddingHorizontal: 16,
    shadowColor: '#000',
    shadowOpacity: 0.04,
    shadowRadius: 8,
    elevation: 2,
  },
  inputIcon: {
    marginRight: 12,
  },
  input: {
    flex: 1,
    paddingVertical: 16,
    fontSize: 16,
    color: COLORS.text,
  },
  eyeButton: {
    padding: 8,
  },
  pickerContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: COLORS.card,
    borderRadius: 12,
    marginBottom: 16,
    paddingHorizontal: 16,
    shadowColor: '#000',
    shadowOpacity: 0.04,
    shadowRadius: 8,
    elevation: 2,
  },
  picker: {
    flex: 1,
    paddingVertical: 16,
  },
  error: {
    color: COLORS.error,
    textAlign: 'center',
    marginBottom: 16,
    fontSize: 14,
  },
  loginButton: {
    backgroundColor: COLORS.primary,
    borderRadius: 12,
    paddingVertical: 16,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 20,
    marginBottom: 16,
  },
  loginButtonDisabled: {
    opacity: 0.7,
  },
  loginButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
    marginLeft: 8,
  },
  forgotPassword: {
    alignItems: 'center',
  },
  forgotPasswordText: {
    color: COLORS.primary,
    fontSize: 14,
  },
}); 