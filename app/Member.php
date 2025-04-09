<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Model
{
    use Notifiable;
    use SoftDeletes;

    const STATUS_PENDING  = 10;
    const STATUS_ACTIVE   = 20;
    const STATUS_EXPIRED  = 30;
    const STATUS_DECLINED = 40;
    const STATUS_DELETED  = 50;

    const MEMBERSHIP_500  = 500;
    const MEMBERSHIP_300  = 300;
    const MEMBERSHIP_200  = 200;
    const MEMBERSHIP_100  = 100;

    protected $table = 'members';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'name', 'email', 'password',
//    ];
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:Y-m-d',
    ];

    protected $appends = [
        'status_text',
        'membership_text',
        'abn_text',
    ];

    public function renderStatus()
    {
        $result = '';
        switch ($this->status) {
            case Member::STATUS_PENDING:
                $result = 'Pending';
                break;
            case Member::STATUS_ACTIVE:
                $result = 'Active';
                break;
            case Member::STATUS_DECLINED:
                $result = 'Declined';
                break;
            case Member::STATUS_EXPIRED:
                $result = 'Expired';
                break;
            case Member::STATUS_DELETED:
                $result = 'Inactive';
                break;
        }

        return $result;
    }

    public function renderMembership()
    {
        $result = '';
        switch ($this->membership) {
            case Member::MEMBERSHIP_500:
                $result = 'Corporate Members: $500/per calendar year';
                break;
            case Member::MEMBERSHIP_300:
                $result = 'Medium Business: $300/per calendar year';
                break;
            case Member::MEMBERSHIP_200:
                $result = 'Professional Business: $200/per calendar year';
                break;
            case Member::MEMBERSHIP_100:
                $result = 'Individuals Professionals And Students: $100/per calendar year';
                break;
        }

        return $result;
    }

    public function renderMembershipShort()
    {
        $result = '';
        switch ($this->membership) {
            case Member::MEMBERSHIP_500:
                $result = 'Corporate Members';
                break;
            case Member::MEMBERSHIP_300:
                $result = 'Medium Business';
                break;
            case Member::MEMBERSHIP_200:
                $result = 'Professional Business';
                break;
            case Member::MEMBERSHIP_100:
                $result = 'Individuals Professionals And Students';
                break;
        }

        return $result;
    }

    public function renderABN()
    {
        $result = $this->abn;

        if (strlen($result) === 11) {
            $result = substr_replace($result, " ", 2, 0);
            $result = substr_replace($result, " ", 6, 0);
            $result = substr_replace($result, " ", 10, 0);
        }

        return $result;
    }

    public function getMembershipTextAttribute($value)
    {
        return $this->renderMembership();
    }

    public function getStatusTextAttribute($value)
    {
        return $this->renderStatus();
    }

    public function getAbnTextAttribute($value)
    {
        return $this->renderABN();
    }
}
