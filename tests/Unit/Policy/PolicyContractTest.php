<?php

namespace Tests\Unit\Policy;

use App\Models\Announcement;
use App\Models\Article;
use App\Models\BookingFlow;
use App\Models\Business;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Image;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Poll\Poll;
use App\Models\Poll\PollOption;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Service;
use App\Models\Todo;
use App\Models\User;
use App\Models\Vote;
use App\Policies\AnnouncementPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\BookingFlowPolicy;
use App\Policies\BusinessPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\FavoritePolicy;
use App\Policies\ImagePolicy;
use App\Policies\NotificationPolicy;
use App\Policies\OfferPolicy;
use App\Policies\PollOptionPolicy;
use App\Policies\PollPolicy;
use App\Policies\ReportPolicy;
use App\Policies\ReservationPolicy;
use App\Policies\ResourcePolicy;
use App\Policies\ServicePolicy;
use App\Policies\TodoPolicy;
use App\Policies\UserPolicy;
use App\Policies\VotePolicy;
use Tests\TestCase;

class PolicyContractTest extends TestCase
{
    public function test_policies_expose_expected_ability_methods(): void
    {
        $expectations = [
            AnnouncementPolicy::class => ['update', 'delete'],
            ArticlePolicy::class => ['update', 'delete'],
            BookingFlowPolicy::class => ['update', 'delete'],
            BusinessPolicy::class => ['manage', 'update', 'delete', 'viewReservations'],
            CategoryPolicy::class => ['update', 'delete'],
            CommentPolicy::class => ['update', 'delete'],
            FavoritePolicy::class => ['update', 'delete'],
            ImagePolicy::class => ['update', 'delete'],
            NotificationPolicy::class => ['view', 'update', 'delete'],
            OfferPolicy::class => ['update', 'delete'],
            PollOptionPolicy::class => ['update', 'delete'],
            PollPolicy::class => ['update', 'delete'],
            ReportPolicy::class => ['update', 'delete'],
            ReservationPolicy::class => ['view', 'update', 'delete'],
            ResourcePolicy::class => ['update', 'delete'],
            ServicePolicy::class => ['update', 'delete'],
            TodoPolicy::class => ['update', 'delete'],
            UserPolicy::class => ['view', 'update', 'delete'],
            VotePolicy::class => ['update', 'delete'],
        ];

        foreach ($expectations as $policyClass => $methods) {
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($policyClass, $method));
            }
        }
    }

    public function test_policies_return_booleans_for_representative_inputs(): void
    {
        $user = $this->makeUser(1);
        $other = $this->makeUser(2, 'admin');
        $business = new Business();
        $business->setRelation('owners', collect([$user]));

        $cases = [
            [new AnnouncementPolicy(), 'update', (new Announcement())->forceFill(['user_id' => 1])],
            [new ArticlePolicy(), 'delete', (new Article())->forceFill(['user_id' => 1])],
            [new BookingFlowPolicy(), 'update', tap((new BookingFlow())->forceFill(['business_id' => 1]), fn ($m) => $m->setRelation('business', $business))],
            [new BusinessPolicy(), 'manage', $business],
            [new CategoryPolicy(), 'update', new Category()],
            [new CommentPolicy(), 'update', (new Comment())->forceFill(['user_id' => 1])],
            [new FavoritePolicy(), 'delete', (new Favorite())->forceFill(['user_id' => 1])],
            [new ImagePolicy(), 'update', tap(new Image(), fn ($m) => $m->setRelation('imageable', (new Offer())->forceFill(['user_id' => 1])))],
            [new NotificationPolicy(), 'view', (new Notification())->forceFill(['user_id' => 1])],
            [new OfferPolicy(), 'update', (new Offer())->forceFill(['user_id' => 1])],
            [new PollOptionPolicy(), 'delete', tap(new PollOption(), fn ($m) => $m->setRelation('poll', (new Poll())->forceFill(['user_id' => 1])))],
            [new PollPolicy(), 'update', (new Poll())->forceFill(['user_id' => 1])],
            [new ReportPolicy(), 'update', (new Report())->forceFill(['user_id' => 1])],
            [new ReservationPolicy(), 'view', tap((new Reservation())->forceFill(['business_id' => 1, 'user_id' => 9]), fn ($m) => $m->setRelation('business', $business))],
            [new ResourcePolicy(), 'update', tap((new Resource())->forceFill(['business_id' => 1]), fn ($m) => $m->setRelation('business', $business))],
            [new ServicePolicy(), 'delete', tap((new Service())->forceFill(['business_id' => 1]), fn ($m) => $m->setRelation('business', $business))],
            [new TodoPolicy(), 'update', (new Todo())->forceFill(['user_id' => 1])],
            [new UserPolicy(), 'view', $this->makeUser(1)],
            [new VotePolicy(), 'delete', (new Vote())->forceFill(['user_id' => 1])],
        ];

        foreach ($cases as [$policy, $method, $subject]) {
            $allowedResult = $policy->{$method}($user, $subject);
            $otherResult = $policy->{$method}($other, $subject);

            $this->assertIsBool($allowedResult);
            $this->assertIsBool($otherResult);
        }
    }

    private function makeUser($id, string $userType = 'user'): User
    {
        $user = new User();
        $user->id = $id;
        $user->user_type = $userType;

        return $user;
    }
}
