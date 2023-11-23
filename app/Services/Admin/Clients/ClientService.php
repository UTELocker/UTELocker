<?php

namespace App\Services\Admin\Clients;

use App\Classes\Common;
use App\Classes\CommonConstant;
use App\Classes\Files;
use App\Enums\ClientStatus;
use App\Exceptions\ApiException;
use App\Models\Client;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Models\CustomToken;
use App\Models\HelpCall;
use App\Models\HelpCallComment;
use App\Models\HelpCallStdProblems;
use App\Models\License;
use App\Models\Locker;
use App\Models\LockerSlot;
use App\Models\LockerSystemLog;
use App\Models\Location;
use App\Models\LocationType;
use App\Models\Notification;
use App\Models\PasswordResetToken;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;

class ClientService extends BaseService
{
    public const FORM_PREFIX = 'client_';

    public function __construct()
    {
        parent::__construct(new Client());
    }

    /**
     * @throws ApiException
     */
    public function add(array $inputs, array $options = []): Model
    {
        $this->new();
        if ($options['isPrefix']) {
            $inputs = Common::mappingRemovePrefix($inputs, self::FORM_PREFIX);
        }
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);

        $this->model->save();

        return $this->model;
    }

    /**
     * @throws ApiException
     */
    protected function  formatInputData(&$inputs): void
    {
        if (!empty($inputs['logo'])) {
            $inputs['logo'] = Files::upload($inputs['logo'], Files::CLIENT_LOGO_FOLDER, width: 300);
        }
        $inputs['name'] = $inputs['name'] ?? $this->model->name;
        $inputs['app_name'] = $inputs['app_name'] ?? $this->model->app_name;
        $inputs['email'] = $inputs['email'] ?? $this->model->email;
        $inputs['phone'] = $inputs['phone'] ?? $this->model->phone;
        $inputs['logo'] = $inputs['logo'] ?? $this->model->logo;
        $inputs['address'] = $inputs['address'] ?? $this->model->address;
        $inputs['website'] = $inputs['website'] ?? $this->model->website;
        $inputs['date_format'] = $inputs['date_format'] ?? $this->model->date_format;
        $inputs['locale'] = $inputs['locale'] ?? $this->model->locale;
        $inputs['time_format'] = $inputs['time_format'] ?? $this->model->time_format;
        $inputs['timezone'] = $inputs['timezone'] ?? $this->model->timezone;
        $inputs['status'] = $inputs['status'] ?? $this->model->status;
        $inputs['allow_signup'] = $inputs['allow_signup'] ?? $this->model->allow_signup;
        $inputs['config_policy'] = $inputs['config_policy'] ?? $this->model->config_policy;
        $inputs['refund_soon_cancel_booking'] = $inputs['refund_soon_cancel_booking'] ?? $this->model->refund_soon_cancel_booking;
        $inputs['email_mailer'] = $inputs['email_mailer'] ?? $this->model->email_mailer;
        $inputs['email_host'] = $inputs['email_host'] ?? $this->model->email_host;
        $inputs['email_port'] = $inputs['email_port'] ?? $this->model->email_port;
        $inputs['email_username'] = $inputs['email_username'] ?? $this->model->email_username;
        $inputs['email_password'] = $inputs['email_password'] ?? $this->model->email_password;
        $inputs['email_encryption'] = $inputs['email_encryption'] ?? $this->model->email_encryption;
        $inputs['email_from_address'] = $inputs['email_from_address'] ?? $this->model->email_from_address;
    }

    protected function setModelFields($inputs): void
    {
        Common::assignField($this->model, 'name', $inputs);
        Common::assignField($this->model, 'app_name', $inputs);
        Common::assignField($this->model, 'email', $inputs);
        Common::assignField($this->model, 'phone', $inputs);
        Common::assignField($this->model, 'logo', $inputs);
        Common::assignField($this->model, 'address', $inputs);
        Common::assignField($this->model, 'website', $inputs);
        Common::assignField($this->model, 'date_format', $inputs);
        Common::assignField($this->model, 'time_format', $inputs);
        Common::assignField($this->model, 'timezone', $inputs);
        Common::assignField($this->model, 'locale', $inputs);
        Common::assignField($this->model, 'status', $inputs);
        Common::assignField($this->model, 'allow_signup', $inputs);
        Common::assignField($this->model, 'config_policy', $inputs);
        Common::assignField($this->model, 'refund_soon_cancel_booking', $inputs);
        Common::assignField($this->model, 'email_mailer', $inputs);
        Common::assignField($this->model, 'email_host', $inputs);
        Common::assignField($this->model, 'email_port', $inputs);
        Common::assignField($this->model, 'email_username', $inputs);
        Common::assignField($this->model, 'email_password', $inputs);
        Common::assignField($this->model, 'email_encryption', $inputs);
        Common::assignField($this->model, 'email_from_address', $inputs);
    }

    public function get($id)
    {
        return $this->model->hasPermission()->findOrFail($id);
    }

    /**
     * @throws ApiException
     */
    public function update(Client $client, array $inputs, array $options = []): Client
    {
        $this->setModel($client);
        if ($options['isPrefix']) {
            $inputs = Common::mappingRemovePrefix($inputs, self::FORM_PREFIX);
        }
        $this->formatInputData($inputs);
        $this->setModelFields($inputs);
        $this->model->save();

        return $client;
    }

    public function getListClientForGuest($id = null)
    {
        return $this->model
            ->select('id', 'name')
            ->when(empty($id), fn ($query) => $query->where('status', ClientStatus::PUBLIC))
            ->when($id, fn ($query) => $query->where('id', $id))
            ->get();
    }
    public function initDefaultData(): static
    {
        $this->model->date_format = 'd-m-Y';
        $this->model->time_format = 'H:i';
        $this->model->timezone = 'Asia/Ho_Chi_Minh';
        $this->model->locale = 'vi';
        $this->model->status = ClientStatus::PUBLIC;
        $this->model->allow_signup = CommonConstant::DATABASE_YES;
        $this->model->refund_soon_cancel_booking = 30;

        return $this;
    }
    public function getClientByLicenseId($licenseId)
    {
        return $this->model
            ->leftJoin('licenses', 'licenses.client_id', '=', 'clients.id')
            ->leftJoin('lockers', 'lockers.id', '=', 'licenses.locker_id')
            ->where('licenses.id', $licenseId)
            ->select('clients.name', 'clients.phone', 'clients.logo',
                'lockers.code', 'lockers.status')
            ->first();
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            Notification::where('client_id', $id)->delete();
            HelpCallComment::where('client_id', $id)->delete();
            HelpCall::where('client_id', $id)->delete();
            HelpCallStdProblems::where('client_id', $id)->delete();
            LocationType::where('client_id', $id)->delete();
            Location::where('client_id', $id)->delete();
            Booking::where('client_id', $id)->delete();
            LockerSlot::leftJoin('Licenses', 'Licenses.locker_id', '=', 'locker_slots.locker_id')
                ->where('Licenses.client_id', $id)->delete();
            Locker::leftJoin('Licenses', 'Licenses.locker_id', '=', 'lockers.id')
                ->where('Licenses.client_id', $id)->delete();
            License::where('client_id', $id)->delete();
            PaymentMethod::where('client_id', $id)->delete();
            CustomToken::where('client_id', $id)->delete();
            LockerSystemLog::where('client_id', $id)->delete();
            PasswordResetToken::where('client_id', $id)->delete();
            Transaction::leftJoin('users', 'users.id', '=', 'transactions.user_id')
                ->where('users.client_id', $id)->delete();
            Wallet::leftJoin('users', 'users.id', '=', 'wallets.user_id')
                ->where('users.client_id', $id)->delete();
            User::where('client_id', $id)->delete();
            $this->model->where('id', $id)->delete();
        });
    }
}
