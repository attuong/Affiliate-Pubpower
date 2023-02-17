<?php

use core\Model;

/**
 * Description of DomainModel
 *
 * @author Tuan
 */
class DomainModel extends Model {

    public function __construct($config = []) {
        parent::__construct($config);
        $this->setDefaultTable(TABLE_DOMAIN);
    }

    //    public function handle_inputs_for_filters($inputs) {
    //        return $inputs;
    //    }

    public function list_by_filters($filters = [], $limit = FALSE) {
        $user = UserModel::getInstance()->get_user_login();
        if (!$user) {
            return [];
        }
        $publishers = UserModel::getInstance()->getPublishersByAffiliate($user->id);
        if (!$publishers) {
            return [];
        }
        $PubIds = get_array_by_key_of_array($publishers, "id", "object");
        $query = ['user_id' => ['$in' => $PubIds]];
        if (!empty($filters['domain_id'])) {
            $query['domain_id'] = $filters['domain_id'];
        }

        $order = ['id' => 'DESC'];
        $domains = $this->find($query, [], $order, $limit);
        return $domains;
    }

    public function getAllDomainForAffiliate() {
        $user = UserModel::getInstance()->get_user_login();
        $Pubs = UserModel::getInstance()->getPublishersByAffiliate($user->id);
        if (!$Pubs) {
            return [];
        }
        $PubIDs = get_array_by_key_of_array($Pubs, "id", 'object');
        $query = [
            'user_id' => ['$in' => $PubIDs],
        ];
        $domains = $this->find($query, ["id", "name"]);
        return $domains;
    }

}
