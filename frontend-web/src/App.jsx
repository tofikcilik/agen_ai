import { Navigate, Route, Routes } from 'react-router-dom';
import { useAuth } from './contexts/AuthContext';
import AppLayout from './layouts/AppLayout';
import LoginPage from './pages/LoginPage';
import DashboardPage from './pages/DashboardPage';
import CustomersPage from './pages/CustomersPage';
import MeterReadingsPage from './pages/MeterReadingsPage';
import BillsPage from './pages/BillsPage';
import PaymentsPage from './pages/PaymentsPage';
import ComplaintsPage from './pages/ComplaintsPage';
import FinancialReportsPage from './pages/FinancialReportsPage';
import VillagesPage from './pages/VillagesPage';
import StatisticsPage from './pages/StatisticsPage';

function ProtectedRoute({ children }) {
  const { user } = useAuth();
  return user ? children : <Navigate to="/login" replace />;
}

export default function App() {
  return (
    <Routes>
      <Route path="/login" element={<LoginPage />} />
      <Route
        path="/"
        element={
          <ProtectedRoute>
            <AppLayout />
          </ProtectedRoute>
        }
      >
        <Route index element={<DashboardPage />} />
        <Route path="desa" element={<VillagesPage />} />
        <Route path="pelanggan" element={<CustomersPage />} />
        <Route path="catat-meter" element={<MeterReadingsPage />} />
        <Route path="tagihan" element={<BillsPage />} />
        <Route path="pembayaran" element={<PaymentsPage />} />
        <Route path="keluhan" element={<ComplaintsPage />} />
        <Route path="laporan-keuangan" element={<FinancialReportsPage />} />
        <Route path="statistik" element={<StatisticsPage />} />
      </Route>
    </Routes>
  );
}
