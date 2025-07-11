import api from './api';
import AsyncStorage from '@react-native-async-storage/async-storage';

/**
 * Login for guardian
 * @param {Object} credentials - { username, password }
 * @returns {Promise<Object>} User data and role
 */
async function login(credentials) {
  try {
    const response = await api.post('/users/login.php', credentials);
    return response.data;
  } catch (err) {
    throw err.response?.data?.message || 'Login failed.';
  }
}

const guardianService = {
  // Authentication
  login,
  changePassword: async (passwordData) => {
    try {
      const response = await api.post('/users/update.php', passwordData);
      return response.data;
    } catch (error) {
      throw error.response?.data || { detail: 'Password change failed' };
    }
  },
  // Profile
  getProfile: async () => {
    try {
      const user_id = await AsyncStorage.getItem('user_id');
      const response = await api.get(`/guardians/read.php?user_id=${user_id}`);
      return response.data;
    } catch (error) {
      throw error.response?.data || { detail: 'Failed to fetch profile' };
    }
  },
  // Children
  getChildren: async () => {
    try {
      const user_id = await AsyncStorage.getItem('user_id');
      // First get the guardian profile to get the guardian_id
      const guardianResponse = await api.get(`/guardians/read.php?user_id=${user_id}`);
      const guardian = guardianResponse.data;
      
      if (!guardian || !guardian.id) {
        return [];
      }
      
      // Now use the guardian's id to fetch children
      const response = await api.get(`/children/read.php?guardian_id=${guardian.id}`);
      return response.data;
    } catch (error) {
      throw error.response?.data || { detail: 'Failed to fetch children' };
    }
  },
  getChildDetail: async (childId) => {
    try {
      const response = await api.get(`/children/read.php?id=${childId}`);
      return response.data;
    } catch (error) {
      throw error.response?.data || { detail: 'Failed to fetch child detail' };
    }
  },
  // Growth Records
  getChildGrowthRecords: async (childId) => {
    try {
      const response = await api.get(`/growth_records/read.php?child_id=${childId}`);
      return response.data;
    } catch (error) {
      throw error.response?.data || { detail: 'Failed to fetch growth records' };
    }
  },
  // Vaccination Records
  getChildVaccinationRecords: async (childId) => {
    try {
      const response = await api.get(`/vaccination_records/read.php?child_id=${childId}`);
      return response.data;
    } catch (error) {
      throw error.response?.data || { detail: 'Failed to fetch vaccination records' };
    }
  },
  // Notifications
  getNotifications: async () => {
    try {
      const user_id = await AsyncStorage.getItem('user_id');
      // First get the guardian profile to get the guardian_id
      const guardianResponse = await api.get(`/guardians/read.php?user_id=${user_id}`);
      const guardian = guardianResponse.data;
      
      if (!guardian || !guardian.id) {
        return [];
      }
      
      // Now use the guardian's id to fetch notifications
      const response = await api.get(`/notifications/read.php?guardian_id=${guardian.id}`);
      return response.data;
    } catch (error) {
      throw error.response?.data || { detail: 'Failed to fetch notifications' };
    }
  },
  getWorkerProfile: async () => {
    try {
      const user_id = await AsyncStorage.getItem('user_id');
      const response = await api.get(`/healthcare_workers/read.php?user_id=${user_id}`);
      return response.data;
    } catch (error) {
      throw error.response?.data || { detail: 'Failed to fetch worker profile' };
    }
  },
};

export default guardianService; 