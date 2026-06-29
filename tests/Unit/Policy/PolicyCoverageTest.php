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
use App\Providers\AuthServiceProvider;
use Tests\TestCase;

class PolicyCoverageTest extends TestCase
{
    public function test_all_model_policy_mappings_are_registered(): void
    {
        $provider = new AuthServiceProvider(app());

        $policies = (function () {
            return $this->policies;
        })->call($provider);

        $expected = [
            Announcement::class => AnnouncementPolicy::class,
            Article::class => ArticlePolicy::class,
            BookingFlow::class => BookingFlowPolicy::class,
            Business::class => BusinessPolicy::class,
            Category::class => CategoryPolicy::class,
            Comment::class => CommentPolicy::class,
            Favorite::class => FavoritePolicy::class,
            Image::class => ImagePolicy::class,
            Notification::class => NotificationPolicy::class,
            Offer::class => OfferPolicy::class,
            Poll::class => PollPolicy::class,
            PollOption::class => PollOptionPolicy::class,
            Report::class => ReportPolicy::class,
            Reservation::class => ReservationPolicy::class,
            Resource::class => ResourcePolicy::class,
            Service::class => ServicePolicy::class,
            Todo::class => TodoPolicy::class,
            User::class => UserPolicy::class,
            Vote::class => VotePolicy::class,
        ];

        foreach ($expected as $modelClass => $policyClass) {
            $this->assertArrayHasKey($modelClass, $policies);
            $this->assertSame($policyClass, $policies[$modelClass]);
        }
    }

    public function test_user_id_based_policies_allow_only_owner(): void
    {
        $owner = $this->makeUser(1);
        $other = $this->makeUser(2);

        $announcement = (new Announcement())->forceFill(['user_id' => 1]);
        $article = (new Article())->forceFill(['user_id' => 1]);
        $comment = (new Comment())->forceFill(['user_id' => 1]);
        $favorite = (new Favorite())->forceFill(['user_id' => 1]);
        $notification = (new Notification())->forceFill(['user_id' => 1]);
        $offer = (new Offer())->forceFill(['user_id' => 1]);
        $poll = (new Poll())->forceFill(['user_id' => 1]);
        $report = (new Report())->forceFill(['user_id' => 1]);
        $todo = (new Todo())->forceFill(['user_id' => 1]);
        $vote = (new Vote())->forceFill(['user_id' => 1]);

        $announcementPolicy = new AnnouncementPolicy();
        $articlePolicy = new ArticlePolicy();
        $commentPolicy = new CommentPolicy();
        $favoritePolicy = new FavoritePolicy();
        $notificationPolicy = new NotificationPolicy();
        $offerPolicy = new OfferPolicy();
        $pollPolicy = new PollPolicy();
        $reportPolicy = new ReportPolicy();
        $todoPolicy = new TodoPolicy();
        $votePolicy = new VotePolicy();

        $this->assertTrue($announcementPolicy->update($owner, $announcement));
        $this->assertFalse($announcementPolicy->update($other, $announcement));

        $this->assertTrue($articlePolicy->update($owner, $article));
        $this->assertFalse($articlePolicy->update($other, $article));

        $this->assertTrue($commentPolicy->delete($owner, $comment));
        $this->assertFalse($commentPolicy->delete($other, $comment));

        $this->assertTrue($favoritePolicy->delete($owner, $favorite));
        $this->assertFalse($favoritePolicy->delete($other, $favorite));

        $this->assertTrue($notificationPolicy->view($owner, $notification));
        $this->assertFalse($notificationPolicy->view($other, $notification));

        $this->assertTrue($offerPolicy->update($owner, $offer));
        $this->assertFalse($offerPolicy->update($other, $offer));

        $this->assertTrue($pollPolicy->delete($owner, $poll));
        $this->assertFalse($pollPolicy->delete($other, $poll));

        $this->assertTrue($reportPolicy->update($owner, $report));
        $this->assertFalse($reportPolicy->update($other, $report));

        $this->assertTrue($todoPolicy->delete($owner, $todo));
        $this->assertFalse($todoPolicy->delete($other, $todo));

        $this->assertTrue($votePolicy->update($owner, $vote));
        $this->assertFalse($votePolicy->update($other, $vote));
    }

    public function test_business_owner_based_policies_allow_owner_and_deny_other(): void
    {
        $owner = $this->makeUser(1);
        $other = $this->makeUser(2);

        $business = new Business();
        $business->setRelation('owners', collect([$owner]));

        $bookingFlow = (new BookingFlow())->forceFill(['business_id' => 10]);
        $bookingFlow->setRelation('business', $business);

        $resource = (new Resource())->forceFill(['business_id' => 10]);
        $resource->setRelation('business', $business);

        $service = (new Service())->forceFill(['business_id' => 10]);
        $service->setRelation('business', $business);

        $reservation = (new Reservation())->forceFill(['business_id' => 10, 'user_id' => 99]);
        $reservation->setRelation('business', $business);

        $businessPolicy = new BusinessPolicy();
        $bookingFlowPolicy = new BookingFlowPolicy();
        $resourcePolicy = new ResourcePolicy();
        $servicePolicy = new ServicePolicy();
        $reservationPolicy = new ReservationPolicy();

        $this->assertTrue($businessPolicy->manage($owner, $business));
        $this->assertFalse($businessPolicy->manage($other, $business));

        $this->assertTrue($bookingFlowPolicy->update($owner, $bookingFlow));
        $this->assertFalse($bookingFlowPolicy->update($other, $bookingFlow));

        $this->assertTrue($resourcePolicy->update($owner, $resource));
        $this->assertFalse($resourcePolicy->update($other, $resource));

        $this->assertTrue($servicePolicy->delete($owner, $service));
        $this->assertFalse($servicePolicy->delete($other, $service));

        $this->assertTrue($reservationPolicy->view($owner, $reservation));
        $this->assertFalse($reservationPolicy->view($other, $reservation));
    }

    public function test_poll_option_policy_uses_poll_owner(): void
    {
        $owner = $this->makeUser(1);
        $other = $this->makeUser(2);

        $poll = (new Poll())->forceFill(['user_id' => 1]);
        $pollOption = new PollOption();
        $pollOption->setRelation('poll', $poll);

        $policy = new PollOptionPolicy();

        $this->assertTrue($policy->update($owner, $pollOption));
        $this->assertFalse($policy->update($other, $pollOption));
    }

    public function test_image_policy_uses_imageable_owner(): void
    {
        $owner = $this->makeUser(1);
        $other = $this->makeUser(2);

        $image = new Image();
        $image->setRelation('imageable', (new Offer())->forceFill(['user_id' => 1]));

        $policy = new ImagePolicy();

        $this->assertTrue($policy->delete($owner, $image));
        $this->assertFalse($policy->delete($other, $image));
    }

    public function test_category_and_user_policies_support_admins(): void
    {
        $admin = $this->makeUser(1, 'admin');
        $regularUser = $this->makeUser(2, 'user');
        $targetUser = $this->makeUser(3, 'user');

        $categoryPolicy = new CategoryPolicy();
        $userPolicy = new UserPolicy();

        $this->assertTrue($categoryPolicy->update($admin, new Category()));
        $this->assertFalse($categoryPolicy->update($regularUser, new Category()));

        $this->assertTrue($userPolicy->update($admin, $targetUser));
        $this->assertTrue($userPolicy->update($targetUser, $targetUser));
        $this->assertFalse($userPolicy->update($regularUser, $targetUser));
    }

    private function makeUser(int $id, string $userType = 'user'): User
    {
        $user = new User();
        $user->id = $id;
        $user->user_type = $userType;

        return $user;
    }
}
