import { ThemeProvider, DarkTheme, DefaultTheme } from '@react-navigation/native';
import { useFonts } from 'expo-font';
import { Slot, useRouter, useSegments, useRootNavigationState } from 'expo-router';
import { StatusBar } from 'expo-status-bar';
import React, { useEffect } from 'react';
import { useColorScheme } from '@/hooks/useColorScheme';
import { AuthProvider, useAuth } from './AuthContext';

function RootLayoutNav() {
  const { isAuthenticated, loading, user } = useAuth();
  const router = useRouter();
  const segments = useSegments();
  const navigationState = useRootNavigationState();

  useEffect(() => {
    if (!navigationState?.key || loading) return;

    if (isAuthenticated) {
      if (segments[0] === 'welcome') {
        switch (user?.role) {
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
            router.replace('/welcome');
        }
      }
    } else if (
      segments[0] === '(tabs)'
      || segments[0] === 'health-worker-dashboard'
      || segments[0] === 'facility-admin-dashboard'
      || segments[0] === 'system-admin-dashboard'
    ) {
      router.replace('/welcome');
    }
  }, [isAuthenticated, user, segments, navigationState, loading]);

  return <Slot />;
}

export default function RootLayout() {
  const colorScheme = useColorScheme();
  const [loaded] = useFonts({
    SpaceMono: require('../assets/fonts/SpaceMono-Regular.ttf'),
  });

  if (!loaded) {
    return null; // show a splash maybe
  }

  return (
    <AuthProvider>
      <ThemeProvider value={colorScheme === 'dark' ? DarkTheme : DefaultTheme}>
        <RootLayoutNav />
        <StatusBar style="dark" backgroundColor="#fff" translucent={false} />
      </ThemeProvider>
    </AuthProvider>
  );
}
