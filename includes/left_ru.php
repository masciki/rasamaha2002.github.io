<?php
$cusers		= mysql_num_rows(mysql_query("SELECT id FROM users")) + cfgSET('fakeusers');
$cwm		= mysql_num_rows(mysql_query("SELECT id FROM users WHERE pm_balance != 0 OR lr_balance != 0")) + cfgSET('fakeactiveusers');

$money	= cfgSET('fakewithdraws');
$query	= "SELECT sum FROM output WHERE status = 2";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$money = $money + $row['sum'];
}

$depmoney	= cfgSET('fakedeposits');
$query	= "SELECT sum FROM deposits WHERE status = 0";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$depmoney = $depmoney + $row['sum'];
}
?>
<table width="100%"><div class="progress-content">
                      <!-- Item -->
                      <div class="bars-item">
                          <div class="progress-texts">
                              <span class="progress-name">Работает с:</span>
                              <span class="progress-value"><?php print date("d.m.Y", cfgSET('datestart')); ?></span>
                              <div class="clear"></div>
                          </div>
                         
                      </div>
                      <!-- End Item -->
                      <!-- Item -->
                      <div class="bars-item">
                          <div class="progress-texts">
                              <span class="progress-name">Пользователей:</span>
                              <span class="progress-value"><?php print $cusers; ?></span>
                              <div class="clear"></div>
                          </div>
                         
                      </div>
                      <!-- End Item -->
                      <!-- Item -->
                      <div class="bars-item">
                          <div class="progress-texts">
                              <span class="progress-name">Активных пользователей:</span>
                              <span class="progress-value"><?php print $cwm; ?></span>
                              <div class="clear"></div>
                          </div>
                         
                      </div>
                      <!-- End Item -->
                      <!-- Item -->
                      <div class="bars-item">
                          <div class="progress-texts">
                              <span class="progress-name">Пользователей online:</span>
                              <span class="progress-value"><?php print intval(mysql_num_rows(mysql_query("SELECT id FROM users WHERE go_time > ".intval(time() - 1200))) + cfgSET('fakeonline')); ?></span>
                              <div class="clear"></div>
                          </div>
                         
                      </div>
                      <!-- End Item -->
                      <!-- Item -->
                      <div class="bars-item">
                          <div class="progress-texts">
                              <span class="progress-name">Депозитов:</span>
                              <span class="progress-value"><?php print $depmoney; ?>$</span>
                              <div class="clear"></div>
                          </div>
                         
                      </div>
                      <!-- End Item -->
                      <!-- Item -->
                      <div class="bars-item">
                          <div class="progress-texts">
                              <span class="progress-name">Выплачено:</span>
                              <span class="progress-value"><?php print $money; ?>$</span>
                              <div class="clear"></div>
                          </div>
                         
                      </div>
                      <!-- End Item -->
                      <!-- Item -->
                      <div class="bars-item">
                          <div class="progress-texts">
                              <span class="progress-name">Новый пользователь:</span>
                              <span class="progress-value"><?php
	$nu	= mysql_fetch_array(mysql_query("SELECT login FROM users ORDER BY id DESC LIMIT 1"));
?> <?php print $nu['login']; ?> </span>
                              <div class="clear"></div>
                          </div>
                         
                      </div>
                      <!-- End Item -->
                      <!-- Item -->
                      <div class="bars-item">
                          <div class="progress-texts">
                              <span class="progress-name">Новый депозит:</span>
                              <span class="progress-value"><?php
	$nd	= mysql_fetch_array(mysql_query("SELECT * FROM deposits ORDER BY id DESC LIMIT 1"));
?><?php print $nd['sum']; ?>$  <?php print $nd['username']; ?>  </span>
                              <div class="clear"></div>
                          </div>
                         
                      </div>
                      <!-- End Item -->
                      <!-- Item -->
                      <div class="bars-item">
                          <div class="progress-texts">
                              <span class="progress-name">Новая выплата:</span>
                              <span class="progress-value"><?php
	$no	= mysql_fetch_array(mysql_query("SELECT * FROM output ORDER BY id DESC LIMIT 1"));
?><?php print $no['sum']; ?>  <?php print $no['login']; ?> </span>
                              <div class="clear"></div>
                          </div>
                         
                      </div>
                      <!-- End Item -->
                      <div>
</table>