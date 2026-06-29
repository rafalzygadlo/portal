<?php

namespace Tests\Unit\Policy;

use App\Models\Announcement;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Poll\Poll;
use App\Models\Report;
use App\Models\Todo;
use App\Models\User;
use App\Models\Vote;
use App\Policies\AnnouncementPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\CommentPolicy;
use App\Policies\FavoritePolicy;
use App\Policies\NotificationPolicy;
use App\Policies\OfferPolicy;
use App\Policies\PollPolicy;
use App\Policies\ReportPolicy;
use App\Policies\TodoPolicy;
use App\Policies\VotePolicy;
use Tests\TestCase;

class PolicyUserOwnedMatrixTest extends TestCase
{
    public function test_owner_based_policies_matrix_for_allow_and_deny(): void
    {
        $owner = $this->makeUser(10);
        $other = $this->makeUser(99);

        $matrix = [
            [new AnnouncementPolicy(), (new Announcement())->forceFill(['user_id' => 10]), ['update', 'delete']],
            [new ArticlePolicy(), (new Article())->forceFill(['user_id' => 10]), ['update', 'delete']],
            [new CommentPolicy(), (new Comment())->forceFill(['user_id' => 10]), ['update', 'delete']],
            [new FavoritePolicy(), (new Favorite())->forceFill(['user_id' => 10]), ['update', 'delete']],
            [new NotificationPolicy(), (new Notification())->forceFill(['user_id' => 10]), ['view', 'update', 'delete']],
            [new OfferPolicy(), (new Offer())->forceFill(['user_id' => 10]), ['update', 'delete']],
            [new PollPolicy(), (new Poll())->forceFill(['user_id' => 10]), ['update', 'delete']],
            [new ReportPolicy(), (new Report())->forceFill(['user_id' => 10]), ['update', 'delete']],
            [new TodoPolicy(), (new Todo())->forceFill(['user_id' => 10]), ['update', 'delete']],
            [new VotePolicy(), (new Vote())->forceFill(['user_id' => 10]), ['update', 'delete']],
        ];

        foreach ($matrix as [$policy, $subject, $abilities]) {
            foreach ($abilities as $ability) {
                $this->assertTrue($policy->{$ability}($owner, $subject));
                $this->assertFalse($policy->{$ability}($other, $subject));
            }
        }
    }

    public function test_owner_checks_work_with_mixed_scalar_types(): void
    {
        $ownerAsInt = $this->makeUser(7);
        $ownerAsString = $this->makeUser('7');
        $other = $this->makeUser(8);

        $announcement = (new Announcement())->forceFill(['user_id' => '7']);
        $favorite = (new Favorite())->forceFill(['user_id' => '7']);
        $notification = (new Notification())->forceFill(['user_id' => '7']);
        $todo = (new Todo())->forceFill(['user_id' => '7']);
        $vote = (new Vote())->forceFill(['user_id' => '7']);

        $this->assertTrue((new AnnouncementPolicy())->update($ownerAsInt, $announcement));
        $this->assertTrue((new FavoritePolicy())->delete($ownerAsString, $favorite));
        $this->assertTrue((new NotificationPolicy())->view($ownerAsInt, $notification));
        $this->assertTrue((new TodoPolicy())->update($ownerAsString, $todo));
        $this->assertTrue((new VotePolicy())->delete($ownerAsInt, $vote));

        $this->assertFalse((new AnnouncementPolicy())->update($other, $announcement));
        $this->assertFalse((new FavoritePolicy())->delete($other, $favorite));
        $this->assertFalse((new NotificationPolicy())->view($other, $notification));
        $this->assertFalse((new TodoPolicy())->update($other, $todo));
        $this->assertFalse((new VotePolicy())->delete($other, $vote));
    }

    private function makeUser($id, string $userType = 'user'): User
    {
        $user = new User();
        $user->id = $id;
        $user->user_type = $userType;

        return $user;
    }
}
