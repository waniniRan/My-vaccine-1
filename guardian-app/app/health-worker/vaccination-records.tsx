import React, { useEffect, useState } from 'react';
import { View, Text, FlatList, Button, TouchableOpacity, StyleSheet, Modal, TextInput } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

export default function VaccinationRecords() {
  const [records, setRecords] = useState<any[]>([]);
  const [modalVisible, setModalVisible] = useState(false);
  const [newRecord, setNewRecord] = useState({ child: '', vaccine: '', date: '' });

  useEffect(() => {
    // TODO: Fetch vaccination records from backend
    setRecords([
      { id: 1, child: 'Alice', vaccine: 'BCG', date: '2024-01-01' },
      { id: 2, child: 'Bob', vaccine: 'DPT', date: '2024-02-01' },
    ]);
  }, []);

  const addRecord = () => {
    // TODO: Send to backend
    setRecords([...records, { ...newRecord, id: Date.now() }]);
    setModalVisible(false);
    setNewRecord({ child: '', vaccine: '', date: '' });
  };

  const deleteRecord = (id: number) => {
    // TODO: Delete from backend
    setRecords(records.filter(r => r.id !== id));
  };

  return (
    <SafeAreaView style={{ flex: 1 }}>
      <Text style={styles.title}>Vaccination Records</Text>
      <Button title="Add Vaccination Record" onPress={() => setModalVisible(true)} />
      <FlatList
        data={records}
        keyExtractor={item => item.id.toString()}
        renderItem={({ item }) => (
          <View style={styles.record}>
            <Text>{item.child}: {item.vaccine} on {item.date}</Text>
            <TouchableOpacity onPress={() => deleteRecord(item.id)}>
              <Text style={{ color: 'red' }}>Delete</Text>
            </TouchableOpacity>
          </View>
        )}
      />
      <Modal visible={modalVisible} animationType="slide">
        <View style={styles.modalContent}>
          <TextInput
            placeholder="Child Name"
            value={newRecord.child}
            onChangeText={text => setNewRecord({ ...newRecord, child: text })}
            style={styles.input}
          />
          <TextInput
            placeholder="Vaccine"
            value={newRecord.vaccine}
            onChangeText={text => setNewRecord({ ...newRecord, vaccine: text })}
            style={styles.input}
          />
          <TextInput
            placeholder="Date (YYYY-MM-DD)"
            value={newRecord.date}
            onChangeText={text => setNewRecord({ ...newRecord, date: text })}
            style={styles.input}
          />
          <Button title="Save" onPress={addRecord} />
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