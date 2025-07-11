import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image, ScrollView } from 'react-native';
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
  danger: '#ff3b30',
};

export default function WelcomeScreen() {
  const handleLogin = () => {
    router.push('/login');
  };

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView contentContainerStyle={styles.content} showsVerticalScrollIndicator={false}>
        {/* Header with Icon */}
        <View style={styles.header}>
          <View style={styles.iconContainer}>
            <Ionicons name="medical" size={80} color={COLORS.primary} />
          </View>
          <Text style={styles.title}>My Vaccine</Text>
          <Text style={styles.subtitle}>Child Vaccination Management System</Text>
        </View>

        {/* Features Section */}
        <View style={styles.featuresContainer}>
          <View style={styles.featureCard}>
            <Ionicons name="shield-checkmark" size={32} color={COLORS.secondary} />
            <Text style={styles.featureTitle}>Safe & Secure</Text>
            <Text style={styles.featureText}>Your child's health records are protected</Text>
          </View>
          
          <View style={styles.featureCard}>
            <Ionicons name="calendar" size={32} color={COLORS.primary} />
            <Text style={styles.featureTitle}>Track Vaccinations</Text>
            <Text style={styles.featureText}>Never miss an important vaccination</Text>
          </View>
          
          <View style={styles.featureCard}>
            <Ionicons name="trending-up" size={32} color={COLORS.secondary} />
            <Text style={styles.featureTitle}>Growth Monitoring</Text>
            <Text style={styles.featureText}>Monitor your child's development</Text>
          </View>
        </View>

        {/* Login Button */}
        <TouchableOpacity style={styles.loginButton} onPress={handleLogin}>
          <Ionicons name="log-in" size={24} color="#fff" />
          <Text style={styles.loginButtonText}>Get Started</Text>
        </TouchableOpacity>

        {/* Footer */}
        <View style={styles.footer}>
          <Text style={styles.footerText}>© 2025 My Vaccine System</Text>
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
  content: {
    flexGrow: 1,
    padding: 20,
    justifyContent: 'space-between',
  },
  header: {
    alignItems: 'center',
    marginTop: 60,
  },
  iconContainer: {
    width: 120,
    height: 120,
    borderRadius: 60,
    backgroundColor: '#eaf1fb',
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 20,
  },
  title: {
    fontSize: 32,
    fontWeight: 'bold',
    color: COLORS.primary,
    marginBottom: 8,
  },
  subtitle: {
    fontSize: 16,
    color: COLORS.gray,
    textAlign: 'center',
  },
  featuresContainer: {
    marginVertical: 40,
  },
  featureCard: {
    backgroundColor: COLORS.card,
    borderRadius: 16,
    padding: 20,
    marginBottom: 16,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOpacity: 0.04,
    shadowRadius: 8,
    elevation: 2,
  },
  featureTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: COLORS.text,
    marginTop: 12,
    marginBottom: 4,
  },
  featureText: {
    fontSize: 14,
    color: COLORS.gray,
    textAlign: 'center',
  },
  loginButton: {
    backgroundColor: COLORS.primary,
    borderRadius: 24,
    paddingVertical: 16,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 20,
  },
  loginButtonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
    marginLeft: 8,
  },
  footer: {
    alignItems: 'center',
  },
  footerText: {
    color: COLORS.gray,
    fontSize: 12,
  },
}); 