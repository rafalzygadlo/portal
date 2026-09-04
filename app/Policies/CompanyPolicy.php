<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;
use App\Policies\Concerns\ChecksCompanyOwnership;

class CompanyPolicy
{
    use ChecksCompanyOwnership;
    
    public function manage(User $user, Company $company): bool
    {   
        return $this->userBelongsToCompany($user, $company);
    }
    /**
     * Whether the user can manage the company.
     */
    public function update(User $user, Company $company): bool
    {
        return $this->userOwnsCompany($user, $company);
    }

    /**
     * Whether the user can delete the company.
     */
    public function delete(User $user, Company $company): bool
    {
        return $this->userOwnsCompany($user, $company);
    }

    /**
     * Whether the user can view reservations.
     */
    public function viewReservations(User $user, Company $company): bool
    {
        return $this->userOwnsCompany($user, $company);
    }
}
