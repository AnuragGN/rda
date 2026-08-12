<?php
/**
 * Created by PhpStorm.
 * User: alkesh
 * Date: 9/16/2021
 * Time: 4:55 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthGroup extends Model
{
    /* @var string */
    protected $table = 'auth_group';

    protected $primaryKey = null;
    public $incrementing = false;

    /* @var boolean */
    public $timestamps = false;

    const AG_STUDENT = "Student";
    const AG_FINANCE = "Finance";
    const AG_REGIONAL_AFFILIATE = "Regional Affiliate";
    const AG_ARTICLE_PUBLISHER = "Article Publisher";
    const AG_BOARD_MEMBER = "Board Member";
    const AG_GRANT_SEEKER = "Grant Seeker";
    const AG_SUPER_USER = "Super User";
    const AG_COMMITTEE_MEMBER = "Committee Member";
    const AG_DONOR = "Donor";
    const AG_PROFESSIONAL_ADVISOR = "Professional Advisor";
    const AG_CONTENT_PROVIDER = "Content Provider";
    const AG_FUND_ADMIN = "Fund Admin";
    const AG_STAFF = "Staff";
    const AG_CONTRIBUTOR = "Contributor";
    const AG_GRANT_PUBLISHER = "Grant Publisher";
    const AG_CMS = "CMS";
    const AG_FIMS_CONTROLLER = "FIMS Controller";

    // 1 Student
    static public function getStudent() {
        return self::where(['groupname' => self::AG_STUDENT])->first();
    }
    static public function getStudentId() {
        $model = self::getStudent();
        return $model ? $model->auth_group_id : null;
    }

    // 2 Finance
    static public function getFinance() {
        return self::where(['groupname' => self::AG_FINANCE])->first();
    }
    static public function getFinanceId() {
        $model = self::getFinance();
        return $model ? $model->auth_group_id : null;
    }

    // 3 Regional Affiliate
    static public function getRegionalAffiliate() {
        return self::where(['groupname' => self::AG_REGIONAL_AFFILIATE])->first();
    }
    static public function getRegionalAffiliateId() {
        $model = self::getRegionalAffiliate();
        return $model ? $model->auth_group_id : null;
    }

    // 4 Article Publisher
    static public function getArticlePublisher() {
        return self::where(['groupname' => self::AG_ARTICLE_PUBLISHER])->first();
    }
    static public function getArticlePublisherId() {
        $model = self::getArticlePublisher();
        return $model ? $model->auth_group_id : null;
    }

    // 5 Board Member
    static public function getBoardMember() {
        return self::where(['groupname' => self::AG_BOARD_MEMBER])->first();
    }
    static public function getBoardMemberId() {
        $model = self::getBoardMember();
        return $model ? $model->auth_group_id : null;
    }

    // 6 Grant Seeker
    static public function getGrantSeeker() {
        return self::where(['groupname' => self::AG_GRANT_SEEKER])->first();
    }
    static public function getGrantSeekerId() {
        $model = self::getGrantSeeker();
        return $model ? $model->auth_group_id : null;
    }

    // 7 Super User
    static public function getSuperUser() {
        return self::where(['groupname' => self::AG_SUPER_USER])->first();
    }
    static public function getSuperUserId() {
        $model = self::getSuperUser();
        return $model ? $model->auth_group_id : null;
    }

    // 8 Committee Member
    static public function getCommitteeMember() {
        return self::where(['groupname' => self::AG_COMMITTEE_MEMBER])->first();
    }
    static public function getCommitteeMemberId() {
        $model = self::getCommitteeMember();
        return $model ? $model->auth_group_id : null;
    }

    // 9 Donor
    static public function getDonor() {
        return self::where(['groupname' => self::AG_DONOR])->first();
    }
    static public function getDonorId() {
        $model = self::getDonor();
        return $model ? $model->auth_group_id : null;
    }

    // 10 Professional Advisor
    static public function getProfessionalAdvisor() {
        return self::where(['groupname' => self::AG_PROFESSIONAL_ADVISOR])->first();
    }
    static public function getProfessionalAdvisorId() {
        $model = self::getProfessionalAdvisor();
        return $model ? $model->auth_group_id : null;
    }

    // 11 Content Provider
    static public function getContentProvider() {
        return self::where(['groupname' => self::AG_CONTENT_PROVIDER])->first();
    }
    static public function getContentProviderId() {
        $model = self::getContentProvider();
        return $model ? $model->auth_group_id : null;
    }

    // 12 Fund Admin
    static public function getFundAdmin() {
        return self::where(['groupname' => self::AG_FUND_ADMIN])->first();
    }
    static public function getFundAdminId() {
        $model = self::getFundAdmin();
        return $model ? $model->auth_group_id : null;
    }

    // 13 Staff
    static public function getStaff() {
        return self::where(['groupname' => self::AG_STAFF])->first();
    }
    static public function getStaffId() {
        $model = self::getStaff();
        return $model ? $model->auth_group_id : null;
    }

    // 14 Contributor
    static public function getContributor() {
        return self::where(['groupname' => self::AG_CONTRIBUTOR])->first();
    }
    static public function getContributorId() {
        $model = self::getContributor();
        return $model ? $model->auth_group_id : null;
    }

    // 15 Grant Publisher
    static public function getGrantPublisher() {
        return self::where(['groupname' => self::AG_GRANT_PUBLISHER])->first();
    }
    static public function getGrantPublisherId() {
        $model = self::getGrantPublisher();
        return $model ? $model->auth_group_id : null;
    }

    // 16 CMS
    static public function getCMS() {
        return self::where(['groupname' => self::AG_CMS])->first();
    }
    static public function getCMSId() {
        $model = self::getCMS();
        return $model ? $model->auth_group_id : null;
    }

    // 17 FIMS Controller
    static public function getFIMSController() {
        return self::where(['groupname' => self::AG_FIMS_CONTROLLER])->first();
    }
    static public function getFIMSControllerId() {
        $model = self::getFIMSController();
        return $model ? $model->auth_group_id : null;
    }

    /**
     * @param Contact $contact
     * @return mixed
     */
    static public function isSuperUser($contact)
    {
        $authGroupId = self::getSuperUserId();
        return AuthUserGroupMap::userBelongsToGroup($contact->auth_user_id, $authGroupId);
    }
}
