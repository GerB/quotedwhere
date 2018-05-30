<?php

/**
 *
 * Quoted where main class
 *
 * @copyright (c) 2018 Ger Bruinsma
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace ger\quotedwhere\classes;

class handler
{  
    protected $db;
    protected $user_quoted_table;
    protected $batch_size = 2000;

    public function __construct(\phpbb\db\driver\driver_interface $db, $user_quoted_table) 
    {
        $this->db = $db;
        $this->user_quoted_table = $user_quoted_table;
    }
    
    /**
	* Get a list of quote authors
	*
	* @param  string   $xml Parsed text
	* @return string[] List of authors
	*/
    public function get_quote_authors($xml)
    {
        $authors = $users = array();
		if (strpos($xml, '<QUOTE ') === false)
		{
			return $authors;
		}

		$dom = new \DOMDocument;
		$dom->loadXML($xml);
		$xpath = new \DOMXPath($dom);
		foreach ($xpath->query('//QUOTE/@author') as $author)
		{
			$authors[] = $author->textContent;
		}
        
        if (!empty($authors))
        {
            $usernames = array_unique($authors);
            $usernames = array_map('utf8_clean_string', $usernames);
            $users = array();

            $sql = 'SELECT user_id
                FROM ' . USERS_TABLE . '
                WHERE ' . $this->db->sql_in_set('username_clean', $usernames);
            $result = $this->db->sql_query($sql);
            while ($row = $this->db->sql_fetchrow($result))
            {
                $users[] = (int) $row['user_id'];
            }
            $this->db->sql_freeresult($result);
        }
        
		return $users;
    }
    
    
    /**
     * Get list of quoted posts from DB
     * @param int $user_id
     * @return array
     */
    public function get_quoted_list($user_id)
    {
        $id_ary = array();
        $sql = 'SELECT post_id FROM ' . $this->user_quoted_table . ' WHERE user_id = ' . (int) $user_id;
        $result = $this->db->sql_query($sql);  
        while ($row = $this->db->sql_fetchrow($result)) 
        {
            $id_ary[] = $row['post_id'];
        }
        $this->db->sql_freeresult($result);
        return $id_ary;
    }
    
    /**
     * Count quoted posts from DB
     * @return int
     */
    public function count_index()
    {
        $sql = 'SELECT count(post_id) as cnt FROM ' . $this->user_quoted_table;
        $result = $this->db->sql_query($sql);  
        $row = $this->db->sql_fetchrow($result);
        return isset($row['cnt']) ? $row['cnt'] : 0;
    }
    
    /**
     * (re)create index
     * @param int $start    Lowest post id to index
     */
    public function create_index($start = 0)
    {
        if ($start === 0)
        {
            // Start with clean sheet
            $truncate = 'TRUNCATE ' . $this->user_quoted_table;
            $this->db->sql_query($truncate);
        }
        // Have we reached the end?
        $sql = 'SELECT max(post_id) end FROM ' . POSTS_TABLE;
        $result = $this->db->sql_query($sql);  
        $row = $this->db->sql_fetchrow($result);
        $end = isset($row['end']) ? $row['end'] : 0;

        if ($start > $end)
        {
            return true;
        }
        
        $max_pid = $start + $this->batch_size;
        // Loop-tee-doo
        for($i=$start; $i<=$max_pid; $i++)
        {
            $post_text = $this->get_post_text($i);
            if (!empty($post_text))
            {
                $this->cleanup_post($i);
                // Get quoted authors
                $users = $this->get_quote_authors($post_text);            
                if (!empty($users)) 
                {
                    $this->add_entries($i, $users);
                }
            }
        }
        // We  have reached our max
        return $max_pid;
    }
    
    /**
     * Get stored post_text
     * @param int $post_id
     * @return string
     */
    private function get_post_text($post_id)
    {
        $sql = 'SELECT post_text FROM ' . POSTS_TABLE . ' WHERE post_id = ' . (int) $post_id;
        $result = $this->db->sql_query($sql);  
        $row = $this->db->sql_fetchrow($result);
        return isset($row['post_text']) ? $row['post_text'] : '';
    }
    
    /**
     * Add entry for array of users
     * @param int $post_id
     * @param array $users
     */
    public function add_entries($post_id, $users)
    {
        foreach ($users as $user_id) 
        {
            if ($user_id != ANONYMOUS)
            {
                $data = ['user_id' => (int) $user_id, 'post_id' => (int) $post_id];
                $action = 'INSERT INTO ' . $this->user_quoted_table . ' ' . $this->db->sql_build_array('INSERT', $data);
                $this->db->sql_query($action);
            }
        }
        return true;
    }

    /**
     * Remove any entry for given post id
     * @param int $post_id
     * @return bool
     */
    public function cleanup_post($post_id)
    {
		$sql = 'DELETE FROM ' . $this->user_quoted_table . '
			WHERE post_id = ' . (int) $post_id;
		return $this->db->sql_query($sql);
    }

    /**
     * Remove any entry for given post id
     * @param int $user_id
     * @return bool
     */
    public function cleanup_user($user_id)
    {
		$sql = 'DELETE FROM ' . $this->user_quoted_table . '
			WHERE user_id = ' . (int) $user_id;
		return $this->db->sql_query($sql);
    }
}