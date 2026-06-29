<?php

namespace Tests\Unit\Policy;

use App\Models\BookingFlow;
use App\Models\Business;
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
use App\Policies\BusinessPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ImagePolicy;
use App\Policies\PollOptionPolicy;
use App\Policies\ReservationPolicy;
use App\Policies\ResourcePolicy;
use App\Policies\ServicePolicy;
use App\Policies\UserPolicy;
use Tests\TestCase;

class PolicyBusinessAndSpecialCasesTest extends TestCase
{
    public function test_business_policy_all_abilities(): void
    {
        $owner = $this->makeUser(1);
        $other = $this->makeUser(2);
        $business = $this->makeBusinessWithOwners([$owner]);

        $policy = new BusinessPolicy();

        foreach (['manage', 'update', 'delete', 'viewReservations'] as $ability) {
            $this->assertTrue($policy->{$ability}($owner, $business));
            $this->assertFalse($policy->{$ability}($other, $business));
        }
    }

    public function test_business_linked_policies_deny_when_business_relation_is_missing(): void
    {
        $user = $this->makeUser(5);

        $bookingFlow = (new BookingFlow())->forceFill(['business_id' => null]);
        $service = (new Service())->forceFill(['business_id' => null]);
        $resource = (new Resource())->forceFill(['business_id' => null, 'user_id' => null]);
        $reservation = (new Reservation())->forceFill(['business_id' => null, 'user_id' => 9]);

        $this->assertFalse((new BookingFlowPolicy())->update($user, $bookingFlow));
        $this->assertFalse((new ServicePolicy())->delete($user, $service));
        $this->assertFalse((new ResourcePolicy())->update($user, $resource));
        $this->assertFalse((new ReservationPolicy())->update($user, $reservation));
    }

    public function test_business_linked_policies_allow_business_owner(): void
    {
        $owner = $this->makeUser(11);
        $other = $this->makeUser(12);
        $business = $this->makeBusinessWithOwners([$owner]);

        $bookingFlow = (new BookingFlow())->forceFill(['business_id' => 1]);
        $bookingFlow->setRelation('business', $business);

        $service = (new Service())->forceFill(['business_id' => 1]);
        $service->setRelation('business', $business);

        $resource = (new Resource())->forceFill(['business_id' => 1]);
        $resource->setRelation('business', $business);

        $reservation = (new Reservation())->forceFill(['business_id' => 1, 'user_id' => 999]);
        $reservation->setRelation('business', $business);

        $this->assertTrue((new BookingFlowPolicy())->delete($owner, $bookingFlow));
        $this->assertFalse((new BookingFlowPolicy())->delete($other, $bookingFlow));

        $this->assertTrue((new ServicePolicy())->update($owner, $service));
        $this->assertFalse((new ServicePolicy())->update($other, $service));

        $this->assertTrue((new ResourcePolicy())->delete($owner, $resource));
        $this->assertFalse((new ResourcePolicy())->delete($other, $resource));

        $this->assertTrue((new ReservationPolicy())->view($owner, $reservation));
        $this->assertFalse((new ReservationPolicy())->view($other, $reservation));
    }

    public function test_resource_and_reservation_policies_allow_record_owner_even_without_business_owner(): void
    {
        $resourceOwner = $this->makeUser(21);
        $reservationOwner = $this->makeUser(31);
        $other = $this->makeUser(99);

        $resource = (new Resource())->forceFill(['business_id' => 1, 'user_id' => 21]);
        $resource->setRelation('business', $this->makeBusinessWithOwners([]));

        $reservation = (new Reservation())->forceFill(['business_id' => 1, 'user_id' => 31]);
        $reservation->setRelation('business', $this->makeBusinessWithOwners([]));

        $this->assertTrue((new ResourcePolicy())->update($resourceOwner, $resource));
        $this->assertFalse((new ResourcePolicy())->update($other, $resource));

        $this->assertTrue((new ReservationPolicy())->delete($reservationOwner, $reservation));
        $this->assertFalse((new ReservationPolicy())->delete($other, $reservation));
    }

    public function test_image_policy_for_offer_owner_business_owner_and_unknown_imageable(): void
    {
        $owner = $this->makeUser(41);
        $other = $this->makeUser(42);

        $offerImage = new Image();
        $offerImage->setRelation('imageable', (new Offer())->forceFill(['user_id' => 41]));

        $businessImage = new Image();
        $businessImage->setRelation('imageable', $this->makeBusinessWithOwners([$owner]));

        $unknownImageableImage = new Image();
        $unknownImageableImage->setRelation('imageable', new Category());

        $policy = new ImagePolicy();

        $this->assertTrue($policy->update($owner, $offerImage));
        $this->assertFalse($policy->update($other, $offerImage));

        $this->assertTrue($policy->delete($owner, $businessImage));
        $this->assertFalse($policy->delete($other, $businessImage));

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
    private function makeBusinessWithOwners(array $owners): Business
    {
        $business = new Business();
        $business->setRelation('owners', collect($owners));

        return $business;
    }
}
