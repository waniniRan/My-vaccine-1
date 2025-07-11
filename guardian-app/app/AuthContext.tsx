import React, { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import guardianService from '../services/guardianService';

interface AuthContextProps {
  isAuthenticated: boolean;
  user: any;
  loading: boolean;
  login: (tokens: { access: string; refresh: string }) => Promise<void>;
  logout: () => Promise<void>;
  refreshProfile: () => Promise<void>;
}

const AuthContext = createContext<AuthContextProps | undefined>(undefined);

export const AuthProvider = ({ children }: { children: ReactNode }) => {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [user, setUser] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const checkAuth = async () => {
      setLoading(true);
      const token = await AsyncStorage.getItem('accessToken');
      if (token) {
        setIsAuthenticated(true);
        try {
          const role = await AsyncStorage.getItem('user_role');
          let profile;
          if (role === 'WORKER') {
            profile = await guardianService.getWorkerProfile();
            console.log('Worker profile:', profile);
            setUser({ ...profile, role: 'WORKER' });
            console.log('Set user (worker):', { ...profile, role: 'WORKER' });
          } else {
            profile = await guardianService.getProfile();
            console.log('Guardian profile:', profile);
            setUser({ ...profile, role: role || 'GUARDIAN' });
            console.log('Set user (guardian):', { ...profile, role: role || 'GUARDIAN' });
          }
          console.log('Role from AsyncStorage:', role);
        } catch (e) {
          setUser(null);
          console.log('Profile fetch error:', e);
        }
      } else {
        setIsAuthenticated(false);
        setUser(null);
      }
      setLoading(false);
    };
    checkAuth();
  }, []);

  const login = async ({ access, refresh }: { access: string; refresh: string }) => {
    setLoading(true);
    try {
      await AsyncStorage.setItem('accessToken', access);
      await AsyncStorage.setItem('refreshToken', refresh);
      setIsAuthenticated(true);
      const role = await AsyncStorage.getItem('user_role');
      let profile;
      if (role === 'WORKER') {
        profile = await guardianService.getWorkerProfile();
        console.log('Worker profile (login):', profile);
        setUser({ ...profile, role: 'WORKER' });
        console.log('Set user (worker, login):', { ...profile, role: 'WORKER' });
      } else {
        profile = await guardianService.getProfile();
        console.log('Guardian profile (login):', profile);
        setUser({ ...profile, role: role || 'GUARDIAN' });
        console.log('Set user (guardian, login):', { ...profile, role: role || 'GUARDIAN' });
      }
      console.log('Role from AsyncStorage (login):', role);
    } finally {
      setLoading(false);
    }
  };

  const logout = async () => {
    setLoading(true);
    await AsyncStorage.removeItem('accessToken');
    await AsyncStorage.removeItem('refreshToken');
    setIsAuthenticated(false);
    setUser(null);
    setLoading(false);
  };

  const refreshProfile = async () => {
    if (isAuthenticated) {
      const profile = await guardianService.getProfile();
      setUser(profile);
    }
  };

  return (
    <AuthContext.Provider value={{ isAuthenticated, user, loading, login, logout, refreshProfile }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) throw new Error('useAuth must be used within an AuthProvider');
  return context;
}; 