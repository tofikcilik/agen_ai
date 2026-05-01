import { useEffect, useMemo, useState } from 'react';
import PageSection from '../components/PageSection';
import { api, getValidationMessage } from '../lib/api';

const emptyForm = {
  village_id: '',
  name: '',
  phone: '',
  rt: '',
  rw: '',
  address_detail: '',
  latitude: '',
  longitude: '',
  meter_number: '',
  status: 'active',
  tariff_per_m3: 3500,
};

function normalizeForm(form) {
  return {
    ...form,
    village_id: Number(form.village_id),
    latitude: form.latitude === '' ? null : Number(form.latitude),
    longitude: form.longitude === '' ? null : Number(form.longitude),
    tariff_per_m3: Number(form.tariff_per_m3 || 0),
  };
}

export default function CustomersPage() {
  const [customers, setCustomers] = useState([]);
  const [villages, setVillages] = useState([]);
  const [form, setForm] = useState(emptyForm);
  const [editingId, setEditingId] = useState(null);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');

  const activeCustomers = useMemo(() => customers.filter((item) => item.status === 'active').length, [customers]);
  const villageCount = useMemo(() => new Set(customers.map((item) => item.village_id)).size, [customers]);

  async function loadData() {
    setLoading(true);
    setError('');
    try {
      const [customerResponse, villageData] = await Promise.all([
        api.get('/customers'),
        api.get('/villages'),
      ]);
      const customerData = customerResponse?.data || customerResponse || [];
      setCustomers(customerData);
      setVillages(villageData || []);
      if (!form.village_id && villageData?.[0]?.id) {
        setForm((current) => ({ ...current, village_id: String(villageData[0].id) }));
      }
    } catch (err) {
      setError(getValidationMessage(err));
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    loadData();
  }, []);

  function updateField(field, value) {
    setForm((current) => ({ ...current, [field]: value }));
  }

  function resetForm() {
    setEditingId(null);
    setForm({ ...emptyForm, village_id: villages[0]?.id ? String(villages[0].id) : '' });
  }

  function startEdit(customer) {
    setEditingId(customer.id);
    setForm({
      village_id: String(customer.village_id || customer.village?.id || ''),
      name: customer.name || '',
      phone: customer.phone || '',
      rt: customer.rt || '',
      rw: customer.rw || '',
      address_detail: customer.address_detail || '',
      latitude: customer.latitude ?? '',
      longitude: customer.longitude ?? '',
      meter_number: customer.meter_number || '',
      status: customer.status || 'active',
      tariff_per_m3: customer.tariff_per_m3 || 3500,
    });
  }

  async function handleSubmit(event) {
    event.preventDefault();
    setSaving(true);
    setError('');

    try {
      const payload = normalizeForm(form);
      if (editingId) {
        await api.put(`/customers/${editingId}`, payload);
      } else {
        await api.post('/customers', payload);
      }
      resetForm();
      await loadData();
    } catch (err) {
      setError(getValidationMessage(err));
    } finally {
      setSaving(false);
    }
  }

  async function handleDelete(customer) {
    if (!window.confirm(`Hapus pelanggan ${customer.name}?`)) return;
    setError('');
    try {
      await api.delete(`/customers/${customer.id}`);
      await loadData();
    } catch (err) {
      setError(getValidationMessage(err));
    }
  }

  return (
    <div className="stack">
      <PageSection title="Identitas Pelanggan" description="Nomor pelanggan dibuat otomatis oleh backend dengan format kodedesa_nomorpelanggan.">
        <div className="metric-row">
          <div><span>Total Pelanggan</span><strong>{customers.length}</strong></div>
          <div><span>Pelanggan Aktif</span><strong>{activeCustomers}</strong></div>
          <div><span>Desa Terdata</span><strong>{villageCount}</strong></div>
        </div>
      </PageSection>

      <PageSection title={editingId ? 'Edit Pelanggan' : 'Tambah Pelanggan'} description="Desa dan kecamatan mengikuti wilayah yang dipilih. Nomor pelanggan tidak diisi manual.">
        <form className="form-grid" onSubmit={handleSubmit}>
          <label>Nama<input value={form.name} onChange={(event) => updateField('name', event.target.value)} placeholder="Nama pelanggan" required /></label>
          <label>No. HP<input value={form.phone} onChange={(event) => updateField('phone', event.target.value)} placeholder="08xxxxxxxxxx" /></label>
          <label>RT<input value={form.rt} onChange={(event) => updateField('rt', event.target.value)} placeholder="001" /></label>
          <label>RW<input value={form.rw} onChange={(event) => updateField('rw', event.target.value)} placeholder="002" /></label>
          <label>Desa<select value={form.village_id} onChange={(event) => updateField('village_id', event.target.value)} required>{villages.map((village) => <option key={village.id} value={village.id}>{village.name}</option>)}</select></label>
          <label>Status<select value={form.status} onChange={(event) => updateField('status', event.target.value)}><option value="active">Aktif</option><option value="inactive">Nonaktif</option></select></label>
          <label>Latitude<input value={form.latitude} onChange={(event) => updateField('latitude', event.target.value)} placeholder="-6.214620" /></label>
          <label>Longitude<input value={form.longitude} onChange={(event) => updateField('longitude', event.target.value)} placeholder="106.845130" /></label>
          <label>Nomor Meter<input value={form.meter_number} onChange={(event) => updateField('meter_number', event.target.value)} placeholder="Opsional" /></label>
          <label>Tarif/m3<input value={form.tariff_per_m3} onChange={(event) => updateField('tariff_per_m3', event.target.value)} type="number" min="0" /></label>
          <label>Alamat Detail<input value={form.address_detail} onChange={(event) => updateField('address_detail', event.target.value)} placeholder="Jalan, dusun, patokan rumah" /></label>
          <button className="primary-button" disabled={saving} type="submit">{saving ? 'Menyimpan...' : editingId ? 'Update Pelanggan' : 'Simpan Pelanggan'}</button>
          {editingId ? <button className="secondary-button" type="button" onClick={resetForm}>Batal Edit</button> : null}
        </form>
        {error ? <div className="alert danger">{error}</div> : null}
      </PageSection>

      <PageSection title="Data Pelanggan per Desa" description={loading ? 'Memuat data...' : 'Data langsung dari API backend.'}>
        <div className="table-wrap">
          <table className="data-table">
            <thead><tr><th>Nomor</th><th>Nama</th><th>No. HP</th><th>RT/RW</th><th>Desa</th><th>Kecamatan</th><th>Koordinat</th><th>Meter</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
              {customers.map((item) => (
                <tr key={item.id}>
                  <td>{item.customer_number}</td>
                  <td>{item.name}</td>
                  <td>{item.phone || '-'}</td>
                  <td>{item.rt || '-'}/{item.rw || '-'}</td>
                  <td>{item.village?.name || '-'}</td>
                  <td>{item.village?.district?.name || '-'}</td>
                  <td>{item.latitude && item.longitude ? `${item.latitude}, ${item.longitude}` : '-'}</td>
                  <td>{item.meter_number || '-'}</td>
                  <td>{item.status}</td>
                  <td><button className="ghost-button" onClick={() => startEdit(item)}>Edit</button> <button className="ghost-button danger-text" onClick={() => handleDelete(item)}>Hapus</button></td>
                </tr>
              ))}
              {!loading && customers.length === 0 ? <tr><td colSpan="10">Belum ada data pelanggan.</td></tr> : null}
            </tbody>
          </table>
        </div>
      </PageSection>
    </div>
  );
}
