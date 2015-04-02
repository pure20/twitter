<?php

/**
 * Model for tweet
 * @author pure
 *
 */
class Tweet_Model extends CI_Model
{

    public $table = 'tweet';
    public $cacheTime = 1;
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Check for the cached tweet and return tweet cache.
     * 
     * Check for the cached tweet within cached time.
     * 
     * @param string $user
     * @param string $searchKey
     * @return object, or null if not found.
     */
    public function getTweetCache($user, $searchKey)
    {
        $this->db->from($this->table)
            ->where('uid', $user)
            ->where('search_text', $searchKey)
            ->where("created_at >= DATE_SUB('" . date('Y-m-d H:i:s', time()) . "', INTERVAL " . $this->cacheTime ." HOUR)", null, false);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        
        return null;
    }

    /**
     * Save cached tweet
     * 
     * @param array $data
     * @return int inserted ID, or false if fail.     
     */
    public function saveSearch($data)
    {
        $this->db->insert($this->table, $data);
        if ($this->db->affected_rows()) {
            return $this->db->insert_id();
        }
        
        return false;
    }

    /**
     * Retrieve saved search
     * 
     * Get the saved search term that user use to search
     * 
     * @param string $user
     * @return NULL
     */
    public function getSavedSearch($user)
    {
        $this->db->from($this->table)
            ->where('uid', $user)
            ->where("created_at >= DATE_SUB('" . date('Y-m-d H:i:s', time()) . "', INTERVAL " . $this->cacheTime ." HOUR)", null, false)
            ->order_by('created_at DESC');
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        
        return null;
    }
}