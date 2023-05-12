<script type="text/javascript"><!--
  player = <?php echo $id; ?>;
  magic  = <?php echo $user['user_last_attack']; ?>;
//--></script>

<span class="error" id="errortext"></span>

<h1>Attack</h1><br /><div class="attack center">
<span id="status"><?php echo $attack_text; ?></span>

<form action="attack.php" method="post" onsubmit="return attack_player('weapon',1)"><div class="form">
<input type="hidden" name="id" value="<?php echo $id ?>" />
<input type="hidden" name="magic" value="<?php echo $user['user_last_attack']; ?>" />
<input type="hidden" name="type" value="weapon" />
<input type="hidden" name="attack" value="1" />
<input type="submit" id="atkbtn3" value="<?php echo $bgm_weapon[$user['user_weapon']]['attack1']['name'] . ' (' . $bgm_weapon[$user['user_weapon']]['attack1']['turn'] . ')'; ?>" />
</div></form>

<form action="attack.php" method="post" onsubmit="return attack_player('weapon',2)"><div class="form">
<input type="hidden" name="id" value="<?php echo $id ?>" />
<input type="hidden" name="magic" value="<?php echo $user['user_last_attack']; ?>" />
<input type="hidden" name="type" value="weapon" />
<input type="hidden" name="attack" value="2" />
<input type="submit" id="atkbtn4" value="<?php echo $bgm_weapon[$user['user_weapon']]['attack2']['name'] . ' (' . $bgm_weapon[$user['user_weapon']]['attack2']['turn'] . ')'; ?>" />
</div></form>

<form action="attack.php" method="post" onsubmit="return attack_player('class',1)"><div class="form">
<input type="hidden" name="id" value="<?php echo $id ?>" />
<input type="hidden" name="magic" value="<?php echo $user['user_last_attack']; ?>" />
<input type="hidden" name="type" value="class" />
<input type="hidden" name="attack" value="1" />
<input type="submit" id="atkbtn1" value="<?php echo $bgm_class[$user['user_class']]['attack1']['name'] . ' (' . $bgm_class[$user['user_class']]['attack1']['turn'] . ')'; ?>" />
</div></form>

<form action="attack.php" method="post" onsubmit="return attack_player('class',2)"><div class="form">
<input type="hidden" name="id" value="<?php echo $id ?>" />
<input type="hidden" name="magic" value="<?php echo $user['user_last_attack']; ?>" />
<input type="hidden" name="type" value="class" />
<input type="hidden" name="attack" value="2" />
<input type="submit" id="atkbtn2" value="<?php echo $bgm_class[$user['user_class']]['attack2']['name'] . ' (' . $bgm_class[$user['user_class']]['attack2']['turn'] . ')'; ?>" />
</div></form>

</div>



<h1 class="fright">Defender</h1>
<h1>Attacker</h1><br /><div><table class="stats attack">

<tr><th>Name:</th>
<td><a href="profile.php?id=<?php echo $user_id; ?>"><?php echo $user['user_name']; ?></a></td>
<td rowspan="4" class="right icons"><?php if ($user['user_type'] === 'prem') echo display_user_pic($user['user_id']); ?></td>
<td rowspan="7" width="100%">&nbsp;</td>
<td rowspan="4" class="left icons"><?php if ($defend['user_type'] === 'prem') echo display_user_pic($id); ?></td>
<td class="right"><a href="profile.php?id=<?php echo $id; ?>"><?php echo $defend['user_name']; ?></a></td>
<th style="text-align:left">:Name</th></tr>

<tr><th>Level:</th>
<td id="atklvl"><?php echo $user['user_level']; ?></td>
<td id="deflvl" class="right"><?php echo $defend['user_level']; ?></td>
<th style="text-align:left">:Level</th>
</tr>

<tr><th>Fame:</th>
<td id="atkfame"><?php echo $user['user_fame']; ?></td>
<td id="deffame" class="right"><?php echo $defend['user_fame']; ?></td>
<th style="text-align:left">:Fame</th></tr>

<tr><th>Bling:</th>
<td id="atkbling"><?php echo $user['user_bling']; ?></td>
<td id="defbling" class="right"><?php echo $defend['user_bling']; ?></td>
<th style="text-align:left">:Bling</th></tr>

<tr><th>HP:</th>
<td colspan="2" class="tdhp"><?php echo display_hp($user['user_hp'], $user['user_maxhp'], 0, 0, 'atkhp') . '<span id="atkhptxt" class="hp hptext">' . $user['user_hp'] . ' / ' . $user['user_maxhp'] . ' : ' . round($user['user_hp']/$user['user_maxhp']*100); ?>%</span></td>
<td colspan="2" class="tdhp"><?php echo display_hp($defend['user_hp'], $defend['user_maxhp'], 0, 1, 'defhp') . '<span id="defhptxt" class="hp hptext">' . round($defend['user_hp']/$defend['user_maxhp']*100); ?>%</span></td>
<th style="text-align:left">:HP</th></tr>

<tr><th>EXP:</th>
<td colspan="2"><?php echo display_hp($user['user_exp'], $user['user_exp_level'], 1, 0, 'atkexp'); ?><span id="atkexptxt" class="hp hptext"><?php echo $user['user_exp'] . ' / ' . $user['user_exp_level'] . ' : ' . floor($user['user_exp']/$user['user_exp_level']*100); ?>%</span></td>
</tr>

<tr>
<td colspan="3" class="center icons">
  <?php echo display_race_pic($user['user_race']); ?>
  <?php echo display_class_pic($user['user_class']); ?>
  <?php echo display_weapon_pic($user['user_weapon']); ?>
</td>
<td colspan="3" class="center icons">
  <?php echo display_race_pic($defend['user_race']); ?>
  <?php echo display_class_pic($defend['user_class']); ?>
  <?php echo display_weapon_pic($defend['user_weapon']); ?>
</td>
</tr>

</table></div>