import { useEffect, useMemo, useState } from 'react';
import PageSection from '../components/PageSection';
import { api, getValidationMessage } from '../lib/api';

const emptyForm = { code: '', name: '', district_id: '' };

export default function VillagesPage() {
  const [villages, setVillages] = useState([]);
  const [districts, setDistricts] = useState([]);
  const [form, setForm] = useState(emptyForm);
  const [editingId, setEditingId] = useState(null);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');

  const totalCustomers = useMemo(() => villages.reduce((sum, item) => sum + (item.customers_count || 0), 0), [villages]);

  async function loadData() {
    setLoading(true);
    setError('');
    try {
      const [villageData, districtData] = await Promise.all([
        api.get('/villages'),
        api.get('/districts'),
      ]);
      setVillages(villageData || []);
      setDistricts(districtData || []);
      if (!form.district_id && districtData?.[0]?.id) {
        setForm((current) => ({ ...current, district_id: String(districtData[0].id) }));
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

  function startEdit(village) {
    setEditingId(village.id);
    setForm({
      code: village.code || '',
      name: village.name || '',
      district_id: String(village.district_id || village.district?.id || ''),
    });
  }

  function resetForm() {
    setEditingId(null);
    setForm({ ...emptyForm, district_id: districts[0]?.id ? String(districts[0].id) : '' });
  }

  async function handleSubmit(event) {
    event.preventDefault();
    setSaving(true);
    setError('');

    const payload = { ...form, district_id: Number(form.district_id) };

    try {
      if (editingId) {
        await api.put(`/villages/${editingId}`, payload);
      } else {
        await api.post('/villages', payload);
      }
      resetForm();
      await loadData();
    } catch (err) {
      setError(getValidationMessage(err));
    } finally {
      setSaving(false);
    }
  }

  async function handleDelete(village) {
    if (!window.confirm(`Hapus desa ${village.name}?`)) return;
    setError('');
    try {
      await api.delete(`/villages/${village.id}`);
      await loadData();
    } catch (err) {
      setError(getValidationMessage(err));
    }
  }

  return (
    <div className="stack">
      <PageSection title="Daftar Desa" description="CRUD desa di bawah wilayah kecamatan. Kode desa menjadi awalan nomor pelanggan otomatis.">
        <div className="metric-row">
          <div><span>Total Desa</span><strong>{villages.length}</strong></div>
          <div><span>Total Pelanggan</span><strong>{totalCustomers}</strong></div>
          <div><span>Kecamatan Terhubung</span><strong>{districts.length}</strong></div>
        </div>
      </PageSection>

      <PageSection title={editingId ? 'Edit Desa' : 'Tambah Desa'} description="Admin root dan kecamatan dapat mengelola desa sesuai wilayah aksesnya.">
        <form className="form-grid" onSubmit={handleSubmit}>
          <label>Kode Desa<input value={form.code} onChange={(event) => updateField('code', event.target.value)} placeholder="DES001" required /></label>
          <label>Nama Desa<input value={form.name} onChange={(event) => updateField('name', event.target.value)} placeholder="Sumber Jaya" required /></label>
          <label>Kecamatan<select value={form.district_id} onChange={(event) => updateField('district_id', event.target.value)} required>{districts.map((district) => <option key={district.id} value={district.id}>{district.name}</option>)}</select></label>
          <button className="primary-button" disabled={saving} type="submit">{saving ? 'Menyimpan...' : editingId ? 'Update Desa' : 'Simpan Desa'}</button>
          {editingId ? <button className="secondary-button" type="button" onClick={resetForm}>Batal Edit</button> : null}
        </form>
        {error ? <div className="alert danger">{error}</div> : null}
      </PageSection>

      <PageSection title="Data Desa" description={loading ? 'Memuat data...' : 'Data desa langsung dari API backend.'}>
        <div className="table-wrap">
          <table className="data-table">
            <thead><tr><th>Kode</th><th>Desa</th><th>Kecamatan</th><th>Aksi</th></tr></thead>
            <tbody>
              {villages.map((item) => (
                <tr key={item.id}>
                  <td>{item.code}</td>
                  <td>{item.name}</td>
                  <td>{item.district?.name || '-'}</td>
                  <td><button className="ghost-button" onClick={() => startEdit(item)}>Edit</button> <button className="ghost-button danger-text" onClick={() => handleDelete(item)}>Hapus</button></td>
                </tr>
              ))}
              {!loading && villages.length === 0 ? <tr><td colSpan="4">Belum ada data desa.</td></tr> : null}
            </tbody>
          </table>
        </div>
      </PageSection>
    </div>
  );
}
