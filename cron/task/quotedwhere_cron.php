<?php
/**
 *
 * Quoted Where. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, Ger, https://github.com/GerB
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace ger\quotedwhere\cron\task;

/**
 * Quoted Where cron task.
 */
class quotedwhere_cron extends \phpbb\cron\task\base
{
	/**
	 * How often we run the cron (in seconds).
	 * @var int
	 */
	protected $cron_frequency = 21600; // 6 hours

	/** @var \phpbb\config\config */
	protected $config;
    
	/** @var \ger\quotedwhere\classes\handler */
	protected $handler;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config $config Config object
     * @param \ger\quotedwhere\classes\handler QW handler
	 */
	public function __construct(\phpbb\config\config $config, \ger\quotedwhere\classes\handler $handler)
	{
		$this->config = $config;
		$this->handler = $handler;
	}

	/**
	 * Runs this cron task.
	 *
	 * @return void
	 */
	public function run()
	{
		// Run your cron actions here...
		$this->handler->cleanup_deleted();

		// Update the cron task run time here if it hasn't
		// already been done by your cron actions.
		$this->config->set('quotedwhere_cron_last_run', time(), false);
	}

	/**
	 * Returns whether this cron task can run, given current board configuration.
	 *
	 * For example, a cron task that prunes forums can only run when
	 * forum pruning is enabled.
	 *
	 * @return bool
	 */
	public function is_runnable()
	{
		return true;
	}

	/**
	 * Returns whether this cron task should run now, because enough time
	 * has passed since it was last run.
	 *
	 * @return bool
	 */
	public function should_run()
	{
        return $this->config['quotedwhere_cron_last_run'] < (time() - $this->cron_frequency);
	}
}
