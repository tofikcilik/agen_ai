import React, { useState } from 'react';
import { SafeAreaView, ScrollView, StatusBar, StyleSheet, Text, TouchableOpacity, View } from 'react-native';
import { fieldCustomers, fieldComplaints, fieldPayments, fieldReadings } from './mockData';

const tabs = ['Dashboard', 'Meter', 'Pembayaran', 'Keluhan'];

export default function App() {
  const [activeTab, setActiveTab] = useState('Dashboard');

  return (
    <SafeAreaView style={styles.safe}>
      <StatusBar barStyle="dark-content" />
      <ScrollView contentContainerStyle={styles.container}>
        <Text style={styles.eyebrow}>Petugas Lapangan</Text>
        <Text style={styles.title}>Air Bersih Mobile</Text>
        <Text style={styles.subtitle}>Akses cepat untuk catat meter, pembayaran, dan keluhan.</Text>

        <View style={styles.tabRow}>
          {tabs.map((tab) => (
            <TouchableOpacity
              key={tab}
              onPress={() => setActiveTab(tab)}
              style={[styles.tabButton, activeTab === tab && styles.tabButtonActive]}
            >
              <Text style={[styles.tabText, activeTab === tab && styles.tabTextActive]}>{tab}</Text>
            </TouchableOpacity>
          ))}
        </View>

        {activeTab === 'Dashboard' ? <DashboardScreen /> : null}
        {activeTab === 'Meter' ? <SimpleList title="Input Meter Hari Ini" items={fieldReadings} /> : null}
        {activeTab === 'Pembayaran' ? <SimpleList title="Pembayaran Tercatat" items={fieldPayments} /> : null}
        {activeTab === 'Keluhan' ? <SimpleList title="Keluhan Lapangan" items={fieldComplaints} /> : null}

        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Pelanggan Tugas Hari Ini</Text>
          {fieldCustomers.map((item) => (
            <View key={item.id} style={styles.card}>
              <Text style={styles.cardTitle}>{item.name}</Text>
              <Text style={styles.cardMeta}>{item.address}</Text>
            </View>
          ))}
        </View>
      </ScrollView>
    </SafeAreaView>
  );
}

function DashboardScreen() {
  return (
    <View style={styles.metrics}>
      {[
        ['Kunjungan', '18'],
        ['Meter Tercatat', '12'],
        ['Pembayaran', '7'],
        ['Keluhan Baru', '3'],
      ].map(([label, value]) => (
        <View key={label} style={styles.metricCard}>
          <Text style={styles.metricLabel}>{label}</Text>
          <Text style={styles.metricValue}>{value}</Text>
        </View>
      ))}
    </View>
  );
}

function SimpleList({ title, items }) {
  return (
    <View style={styles.section}>
      <Text style={styles.sectionTitle}>{title}</Text>
      {items.map((item) => (
        <View key={item.id} style={styles.card}>
          <Text style={styles.cardTitle}>{item.title}</Text>
          <Text style={styles.cardMeta}>{item.meta}</Text>
        </View>
      ))}
    </View>
  );
}

const styles = StyleSheet.create({
  safe: {
    flex: 1,
    backgroundColor: '#f2f7fa',
  },
  container: {
    padding: 20,
    gap: 18,
  },
  eyebrow: {
    textTransform: 'uppercase',
    color: '#537182',
    fontSize: 12,
  },
  title: {
    fontSize: 28,
    fontWeight: '700',
    color: '#153246',
  },
  subtitle: {
    color: '#547082',
    lineHeight: 22,
  },
  tabRow: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 8,
  },
  tabButton: {
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderRadius: 8,
    backgroundColor: '#e4edf3',
  },
  tabButtonActive: {
    backgroundColor: '#0c7a6b',
  },
  tabText: {
    color: '#204154',
  },
  tabTextActive: {
    color: '#ffffff',
  },
  metrics: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 12,
  },
  metricCard: {
    width: '47%',
    backgroundColor: '#ffffff',
    padding: 16,
    borderRadius: 8,
  },
  metricLabel: {
    color: '#597384',
  },
  metricValue: {
    marginTop: 8,
    fontSize: 24,
    fontWeight: '700',
    color: '#173347',
  },
  section: {
    gap: 10,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: '700',
    color: '#173347',
  },
  card: {
    backgroundColor: '#ffffff',
    borderRadius: 8,
    padding: 16,
  },
  cardTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#173347',
  },
  cardMeta: {
    marginTop: 6,
    color: '#5e7989',
  },
});
