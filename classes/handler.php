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
        // Simple check
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
        for ($i = $start; $i <= $max_pid; $i++)
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
     * @param bool $text_only
     * @return string
     */
    private function get_post_text($post_id, $text_only = true)
    {
        $sql = 'SELECT post_text, bbcode_bitfield, bbcode_uid, enable_bbcode, enable_smilies, enable_magic_url FROM ' . POSTS_TABLE . ' WHERE post_id = ' . (int) $post_id;
        $result = $this->db->sql_query($sql);
        $row = $this->db->sql_fetchrow($result);
        if (!isset($row['post_text']))
        {
            return false;
        }
        if ($text_only)
        {
            return $row['post_text'];
        }
        return $row;
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

    /**
     * Anonymize quotes for given user
     * @param int $user_id
     * @param string $original_name
     * @param string $replacement
     * @return bool
     */
    public function anonymize_user_quotes($user_id, $original_name, $replacement)
    {
        $posts = $this->get_quoted_list($user_id);
        if ($posts)
        {
//            $sql = 'SELECT username FROM ' . USERS_TABLE . ' WHERE user_id = ' .(int) $user_id;
//            $result = $this->db->sql_query($sql);  
//            $row = $this->db->sql_fetchrow($result);
//            $username = $row['username'];
            foreach ($posts as $post_id)
            {
                $post = $this->get_post_text($post_id, false);
                $data = $this->replace_author($post, $original_name, $replacement);

                $sql = 'UPDATE ' . POSTS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $data) . ' WHERE post_id = ' . (int) $post_id;
                $this->db->sql_query($sql);
            }
        }
        return true;
    }

    /**
     * Replace old author name in quotet post with new value
     * @param array $post_data post data required  by generate_text functions
     * @param string $old current author name
     * @param string $new replacing author name
     * @return string
     */
    private function replace_author($post_data, $old, $new)
    {
        if (strpos($post_data['post_text'], '<QUOTE ') === false)
        {
            return $post_data;
        }
        $bbcode_options = (($post_data['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) +
            (($post_data['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) +
            (($post_data['enable_magic_url']) ? OPTION_FLAG_LINKS : 0);
        $decoded = generate_text_for_edit($post_data['post_text'], $post_data['bbcode_uid'], $bbcode_options);

        // Get quotes from decoded message
        $out = preg_replace('~\[quote=(.*?)' . $old . '(.*?)(user_id=[0-9]+)(.*?)\]~is', '[quote=$1' . $new . '$2$4]', $decoded['text']);
//        var_dump($out, $decoded['text'], $old, $new); die;
        $checksum = md5($out);
        $uid = $bitfield = $options = ''; // will be modified by generate_text_for_storage
        generate_text_for_storage($out, $post_data['bbcode_uid'], $post_data['bbcode_bitfield'], $bbcode_options, $post_data['enable_bbcode'], $post_data['enable_magic_url'], $post_data['enable_smilies']);
        return array(
            'post_text' => $out,
            'post_checksum' => $checksum,
        );


//        preg_match_all("#\[quote(.+?)\]>#isU", $decoded['text'], $quotes);
//        foreach ($quotes[1] as $tag)
//        {
//            
//        }
//        
////        generate_text_for_storage($new, $old, $xml, $flags, $allow_bbcode, $allow_urls, $allow_smilies, $allow_img_bbcode, $allow_flash_bbcode, $allow_quote_bbcode)
//        // Easy one first
//        $xml = preg_replace('~\[quote=(.*?)' . $old . '(.*?) ~is', '[quote=$1' . $new . '$2 ', $xml);
//
//        $dom = new \DOMDocument;
//        $dom->loadXML($xml);
//        $quotes = $dom->getElementsByTagName('QUOTE');
//        foreach($quotes as $quote)
//        {
//            if ($quote->hasAttribute('author'))
//            {
//                $quote_author = $quote->getAttribute('author');
//                if (strtoupper($quote_author) == strtoupper($old))
//                {
//                    $quote->removeAttribute('author');
//                    $quote->setAttribute('author', $new);
//                }
//            }
//        }
//        $result = $dom->saveXML();
//        return $result;
    }

}
