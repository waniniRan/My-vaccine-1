import React, { useEffect, useState } from 'react';
import { View, Text, FlatList, Button, TouchableOpacity, StyleSheet, Modal, TextInput } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

export default function GuardianAccounts() {
  const [guardians, setGuardians] = useState<any[]>([]);
  const [modalVisible, setModalVisible] = useState(false);
  const [newGuardian, setNewGuardian] = useState({ name: '', idNumber: '', phone: '' });

  useEffect(() => {
    // TODO: Fetch guardians from backend
    setGuardians([
      { id: 1, name: 'Jane Doe', idNumber: '123456789', phone: '987-654-3210' },
      { id: 2, name: 'Mary Johnson', idNumber: '987654321', phone: '555-123-4567' },
    ]);
  }, []);

  const addGuardian = () => {
    // TODO: Send to backend
    setGuardians([...guardians, { ...newGuardian, id: Date.now() }]);
    setModalVisible(false);
    setNewGuardian({ name: '', idNumber: '', phone: '' });
  };

  const deleteGuardian = (id: number) => {
    // TODO: Delete from backend
    setGuardians(guardians.filter(g => g.id !== id));
  };

  return (
    <SafeAreaView style={{ flex: 1 }}>
      <Text style={styles.title}>Guardian Accounts</Text>
      <Button title="Add Guardian" onPress={() => setModalVisible(true)} />
      <FlatList
        data={guardians}
        keyExtractor={item => item.id.toString()}
        renderItem={({ item }) => (
          <View style={styles.record}>
            <Text>{item.name} ({item.idNumber}) - {item.phone}</Text>
            <TouchableOpacity onPress={() => deleteGuardian(item.id)}>
              <Text style={{ color: 'red' }}>Delete</Text>
            </TouchableOpacity>
          </View>
        )}
      />
      <Modal visible={modalVisible} animationType="slide">
        <View style={styles.modalContent}>
          <TextInput
            placeholder="Name"
            value={newGuardian.name}
            onChangeText={text => setNewGuardian({ ...newGuardian, name: text })}
            style={styles.input}
          />
          <TextInput
            placeholder="ID Number"
            value={newGuardian.idNumber}
            onChangeText={text => setNewGuardian({ ...newGuardian, idNumber: text })}
            style={styles.input}
          />
          <TextInput
            placeholder="Phone"
            value={newGuardian.phone}
            onChangeText={text => setNewGuardian({ ...newGuardian, phone: text })}
            style={styles.input}
          />
          <Button title="Save" onPress={addGuardian} />
          <Button title="Cancel" onPress={() => setModalVisible(false)} />
        </View>
      </Modal>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  title: { fontSize: 24, fontWeight: 'bold', margin: 16 },
  record: { flexDirection: 'row', justifyContent: 'space-between', padding: 16, borderBottomWidth: 1, borderColor: '#eee' },
  modalContent: { flex: 1, justifyContent: 'center', padding: 16 },
  input: { borderWidth: 1, borderColor: '#ccc', padding: 8, marginVertical: 8, borderRadius: 4 },
}); 