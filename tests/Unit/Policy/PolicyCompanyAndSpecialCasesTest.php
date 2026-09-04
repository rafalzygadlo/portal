<?php

namespace Tests\Unit\Policy;

use App\Models\BookingFlow;
use App\Models\Company;
use App\Models\Category;
use App\Models\Image;
use App\Models\Offer;
use App\Models\Poll\Poll;
use App\Models\Poll\PollOption;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Service;
use App\Models\User;
use App\Policies\BookingFlowPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ImagePolicy;
use App\Policies\PollOptionPolicy;
use App\Policies\ReservationPolicy;
use App\Policies\ResourcePolicy;
use App\Policies\ServicePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyCompanyAndSpecialCasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_policy_all_abilities(): void
    {
        $owner = $this->makeUser(1);
        $other = $this->makeUser(2);
        $company = $this->makeCompanyWithOwners([$owner]);

        $policy = new CompanyPolicy();

        foreach (['manage', 'update', 'delete', 'viewReservations'] as $ability) {
            $this->assertTrue($policy->{$ability}($owner, $company));
            $this->assertFalse($policy->{$ability}($other, $company));
        }
    }

    public function test_company_member_can_manage_panel_but_not_change_company(): void
    {
        $owner = $this->makeUser(21);
        $employee = $this->makeUser(22);
        $company = $this->makeCompanyWithOwners([$owner]);
        $company->setRelation('users', collect([$employee]));

        $policy = new CompanyPolicy();

        $this->assertTrue($policy->manage($employee, $company));
        $this->assertFalse($policy->update($employee, $company));
        $this->assertFalse($policy->delete($employee, $company));
    }

    public function test_company_manage_policy_uses_company_model_not_subdomain_string(): void
    {
        $owner = $this->makeUser(10);
        $other = $this->makeUser(11);
        $company = $this->makeCompanyWithOwners([$owner]);
        $company->subdomain = 'demo';

        $this->assertTrue(\Illuminate\Support\Facades\Gate::forUser($owner)->allows('manage', $company));
        $this->assertFalse(\Illuminate\Support\Facades\Gate::forUser($other)->allows('manage', $company));

        $url = route('admin.company.dashboard', ['company' => $company]);
        $this->assertStringContainsString('demo', $url);
        $this->assertStringContainsString('/admin/dashboard', $url);
    }

    public function test_company_linked_policies_deny_when_company_relation_is_missing(): void
    {
        $user = $this->makeUser(5);

        $bookingFlow = (new BookingFlow())->forceFill(['company_id' => null]);
        $service = (new Service())->forceFill(['company_id' => null]);
        $resource = (new Resource())->forceFill(['company_id' => null, 'user_id' => null]);
        $reservation = (new Reservation())->forceFill(['company_id' => null, 'user_id' => 9]);

        $this->assertFalse((new BookingFlowPolicy())->update($user, $bookingFlow));
        $this->assertFalse((new ServicePolicy())->delete($user, $service));
        $this->assertFalse((new ResourcePolicy())->update($user, $resource));
        $this->assertFalse((new ReservationPolicy())->update($user, $reservation));
    }

    public function test_company_linked_policies_allow_company_owner(): void
    {
        $owner = $this->makeUser(11);
        $other = $this->makeUser(12);
        $company = $this->makeCompanyWithOwners([$owner]);

        $bookingFlow = (new BookingFlow())->forceFill(['company_id' => 1]);
        $bookingFlow->setRelation('company', $company);

        $service = (new Service())->forceFill(['company_id' => 1]);
        $service->setRelation('company', $company);

        $resource = (new Resource())->forceFill(['company_id' => 1]);
        $resource->setRelation('company', $company);

        $reservation = (new Reservation())->forceFill(['company_id' => 1, 'user_id' => 999]);
        $reservation->setRelation('company', $company);

        $this->assertTrue((new BookingFlowPolicy())->delete($owner, $bookingFlow));
        $this->assertFalse((new BookingFlowPolicy())->delete($other, $bookingFlow));

        $this->assertTrue((new ServicePolicy())->update($owner, $service));
        $this->assertFalse((new ServicePolicy())->update($other, $service));

        $this->assertTrue((new ResourcePolicy())->delete($owner, $resource));
        $this->assertFalse((new ResourcePolicy())->delete($other, $resource));

        $this->assertTrue((new ReservationPolicy())->view($owner, $reservation));
        $this->assertFalse((new ReservationPolicy())->view($other, $reservation));
    }

    public function test_resource_and_reservation_policies_allow_record_owner_even_without_company_owner(): void
    {
        $resourceOwner = $this->makeUser(21);
        $reservationOwner = $this->makeUser(31);
        $other = $this->makeUser(99);

        $resource = (new Resource())->forceFill(['company_id' => 1, 'user_id' => 21]);
        $resource->setRelation('company', $this->makeCompanyWithOwners([]));

        $reservation = (new Reservation())->forceFill(['company_id' => 1, 'user_id' => 31]);
        $reservation->setRelation('company', $this->makeCompanyWithOwners([]));

        $this->assertTrue((new ResourcePolicy())->update($resourceOwner, $resource));
        $this->assertFalse((new ResourcePolicy())->update($other, $resource));

        $this->assertTrue((new ReservationPolicy())->delete($reservationOwner, $reservation));
        $this->assertFalse((new ReservationPolicy())->delete($other, $reservation));
    }

    public function test_image_policy_for_offer_owner_company_owner_and_unknown_imageable(): void
    {
        $owner = $this->makeUser(41);
        $other = $this->makeUser(42);

        $offerImage = new Image();
        $offerImage->setRelation('imageable', (new Offer())->forceFill(['user_id' => 41]));

        $companyImage = new Image();
        $companyImage->setRelation('imageable', $this->makeCompanyWithOwners([$owner]));

        $unknownImageableImage = new Image();
        $unknownImageableImage->setRelation('imageable', new Category());

        $policy = new ImagePolicy();

        $this->assertTrue($policy->update($owner, $offerImage));
        $this->assertFalse($policy->update($other, $offerImage));

        $this->assertTrue($policy->delete($owner, $companyImage));
        $this->assertFalse($policy->delete($other, $companyImage));

        $this->assertFalse($policy->update($owner, $unknownImageableImage));
        $this->assertFalse($policy->delete($other, $unknownImageableImage));
    }

    public function test_poll_option_policy_and_admin_policies(): void
    {
        $owner = $this->makeUser(51);
        $other = $this->makeUser(52);
        $admin = $this->makeUser(60, 'admin');

        $poll = (new Poll())->forceFill(['user_id' => 51]);
        $pollOption = new PollOption();
        $pollOption->setRelation('poll', $poll);

        $pollOptionWithoutPoll = new PollOption();

        $pollOptionPolicy = new PollOptionPolicy();
        $categoryPolicy = new CategoryPolicy();

        $this->assertTrue($pollOptionPolicy->update($owner, $pollOption));
        $this->assertFalse($pollOptionPolicy->update($other, $pollOption));
        $this->assertFalse($pollOptionPolicy->delete($owner, $pollOptionWithoutPoll));

        $this->assertTrue($categoryPolicy->update($admin, new Category()));
        $this->assertTrue($categoryPolicy->delete($admin, new Category()));
        $this->assertFalse($categoryPolicy->update($other, new Category()));
        $this->assertFalse($categoryPolicy->delete($other, new Category()));
    }

    public function test_user_policy_for_admin_self_and_other_user(): void
    {
        $admin = $this->makeUser(71, 'admin');
        $self = $this->makeUser(72, 'user');
        $other = $this->makeUser(73, 'user');
        $target = $this->makeUser(72, 'user');

        $policy = new UserPolicy();

        foreach (['view', 'update', 'delete'] as $ability) {
            $this->assertTrue($policy->{$ability}($admin, $target));
            $this->assertTrue($policy->{$ability}($self, $target));
            $this->assertFalse($policy->{$ability}($other, $target));
        }
    }

    private function makeUser($id, string $userType = 'user'): User
    {
        $user = new User();
        $user->id = $id;
        $user->user_type = $userType;

        return $user;
    }

    /**
     * @param array<int, User> $owners
     */
    private function makeCompanyWithOwners(array $owners): Company
    {
        $company = new Company();
        $company->setRelation('owners', collect($owners));

        return $company;
    }
}
