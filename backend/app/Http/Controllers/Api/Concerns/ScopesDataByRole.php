<?php

namespace App\Http\Controllers\Api\Concerns;

use App\Models\Bill;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\District;
use App\Models\MeterReading;
use App\Models\Payment;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ScopesDataByRole
{
    protected function perPage(): int
    {
        return max(1, min((int) request('per_page', 20), 100));
    }

    protected function scopeVillages(Builder $query, User $user): Builder
    {
        return match ($user->role?->name) {
            'administrator' => $query,
            'kecamatan' => $query->where('district_id', $user->district_id),
            'desa', 'petugas_lapangan' => $query->whereKey($user->village_id),
            default => $query,
        };
    }

    protected function scopeDistricts(Builder $query, User $user): Builder
    {
        return match ($user->role?->name) {
            'administrator' => $query,
            'kecamatan', 'desa', 'petugas_lapangan' => $query->whereKey($user->district_id),
            default => $query,
        };
    }

    protected function scopeCustomers(Builder $query, User $user): Builder
    {
        return match ($user->role?->name) {
            'administrator' => $query,
            'kecamatan' => $query->whereHas('village', function (Builder $villageQuery) use ($user): void {
                $villageQuery->where('district_id', $user->district_id);
            }),
            default => $query->where('village_id', $user->village_id),
        };
    }

    protected function scopeMeterReadings(Builder $query, User $user): Builder
    {
        return $this->scopeByCustomerVillage($query, $user, 'customer');
    }

    protected function scopeBills(Builder $query, User $user): Builder
    {
        return $this->scopeByCustomerVillage($query, $user, 'customer');
    }

    protected function scopePayments(Builder $query, User $user): Builder
    {
        return $this->scopeByCustomerVillage($query, $user, 'bill.customer');
    }

    protected function scopeComplaints(Builder $query, User $user): Builder
    {
        return $this->scopeByCustomerVillage($query, $user, 'customer');
    }

    protected function ensureVillageAccess(User $user, int $villageId): void
    {
        if ($user->hasRole('administrator')) {
            return;
        }

        if ($user->hasRole('kecamatan')) {
            $isAccessible = Village::query()
                ->whereKey($villageId)
                ->where('district_id', $user->district_id)
                ->exists();

            if (! $isAccessible) {
                throw new HttpException(403, 'Data desa ini tidak dapat diakses oleh role Anda.');
            }

            return;
        }

        if ((int) $user->village_id !== $villageId) {
            throw new HttpException(403, 'Data desa ini tidak dapat diakses oleh role Anda.');
        }
    }

    protected function ensureDistrictAccess(User $user, int $districtId): void
    {
        if ($user->hasRole('administrator')) {
            return;
        }

        if ((int) $user->district_id !== $districtId) {
            throw new HttpException(403, 'Data kecamatan ini tidak dapat diakses oleh role Anda.');
        }
    }

    protected function ensureCustomerAccess(User $user, Customer $customer): void
    {
        $this->ensureVillageAccess($user, (int) $customer->village_id);
    }

    protected function ensureBillAccess(User $user, Bill $bill): void
    {
        $this->ensureCustomerAccess($user, $bill->customer);
    }

    protected function ensurePaymentAccess(User $user, Payment $payment): void
    {
        $this->ensureBillAccess($user, $payment->bill);
    }

    protected function ensureComplaintAccess(User $user, Complaint $complaint): void
    {
        $this->ensureCustomerAccess($user, $complaint->customer);
    }

    protected function ensureMeterReadingAccess(User $user, MeterReading $meterReading): void
    {
        $this->ensureCustomerAccess($user, $meterReading->customer);
    }

    protected function ensureVillageModelAccess(User $user, Village $village): void
    {
        $this->ensureVillageAccess($user, (int) $village->id);
    }

    protected function ensureDistrictModelAccess(User $user, District $district): void
    {
        $this->ensureDistrictAccess($user, (int) $district->id);
    }

    private function scopeByCustomerVillage(Builder $query, User $user, string $relation): Builder
    {
        if ($user->hasRole('administrator')) {
            return $query;
        }

        if ($user->hasRole('kecamatan')) {
            return $query->whereHas($relation, function (Builder $customerQuery) use ($user): void {
                $customerQuery->whereHas('village', function (Builder $villageQuery) use ($user): void {
                    $villageQuery->where('district_id', $user->district_id);
                });
            });
        }

        return $query->whereHas($relation, function (Builder $customerQuery) use ($user): void {
            $customerQuery->where('village_id', $user->village_id);
        });
    }
}
