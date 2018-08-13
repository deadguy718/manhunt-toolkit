<?php
namespace App\Tests\LevelScripts;

use App\Bytecode\Helper;
use App\Service\Archive\Glg;
use App\Service\Archive\Mls;
use App\Service\Compiler\Compiler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class stealthtwoonoffTest extends KernelTestCase
{

    public function test()
    {
//        $this->assertEquals(true, true, 'The bytecode is not correct');
//return;
        $script = "


scriptmain StealthTwoOnOff;

ENTITY
	triggerStealthTwoAware : et_name;
	
VAR
	stealthTwoLooper : level_var boolean;
	stealthTwoHeard : level_var boolean;
	alreadyDone : boolean;
	stealthTwoDone : level_var boolean;
	stealthTwoFacingYou : level_var boolean;
	
script OnCreate;
begin
	stealthTwoHeard := FALSE;
	alreadyDone := FALSE;
end;
	
	
script OnEnterTrigger;
VAR
	pos : vec3d;
begin
	{AIMakeEntityDeaf('StealthTutTwo(hunter)', 1);
	AIMakeEntityBlind('StealthTutTwo(hunter)', 1);}
	{AIMakeEntityBlind('StealthTutTwoTwo(hunter)', 0);
	AIMakeEntityBlind('StealthTutTwoThree(hunter)', 0);}
	
	stealthTwoLooper := TRUE;
	while stealthTwoLooper = TRUE do
	begin
			
		If ( IsPlayerRunning) OR (IsPlayerSprinting) OR (stealthTwoHeard = TRUE) OR ((NOT IsPlayerInSafeZone) AND (GetPedOrientation(GetEntity('StealthTutTwo(hunter)')) < 95.0) AND (GetPedOrientation(GetEntity('StealthTutTwo(hunter)')) > 85.0) ) then
		begin

			if alreadyDone = FALSE then
			begin
			
				sleep(300);
			
{				AIEntityCancelAnim('StealthTutTwo(hunter)', 'ASY_INMATE_HITSELF_SAT');}
				RunScript('StealthTutTwo(hunter)', 'PissOnMe');
				
				while stealthTwoDone <> TRUE do sleep(10);

	
				SetVector(pos, -47.891, 0, 25.86);
				MoveEntity(GetEntity('StealthTutTwo(hunter)'), pos, 1); 	
				SetPedOrientation(GetEntity('StealthTutTwo(hunter)'), 90);
	
				RunScript('triggerStealthTwoAware', 'LoopAfterSound');
				
				AiEntityPlayAnimLooped('StealthTutTwo(hunter)', 'ASY_INMATE_BARS_3', 0.0);
				SetEntityInvulnerable(GetEntity('StealthTutTwo(hunter)'), true);
				
				alreadyDone := TRUE;
			end;
			
			{CHANGE BLIP TO RED}
			RadarPositionSetEntity(GetEntity('StealthTutTwo(hunter)'), MAP_COLOR_RED);
		end;
	end;
	

	RadarPositionClearEntity(GetEntity('StealthTutTwo(hunter)'));
	{RadarPositionClearEntity(GetEntity('StealthTutTwo(hunter)'));}
			
	
end;

script LoopAfterSound;
begin
	{while GetEntity('StealthTutTwo(hunter)') <> NIL do
	begin
		PlayAudioOneShotFromEntity(GetEntity('StealthTutTwo(hunter)'), 'LEVEL', 'INMATE2', 50, 40);
		sleep(GetAnimationLength('ASY_INMATE_BARS_3'));
	end;}
	
	sleep(10000);
	
	AIEntityCancelAnim('StealthTutTwo(hunter)', 'ASY_INMATE_BARS_3');
	AiSetEntityIdleOverride('StealthTutTwo(hunter)', FALSE, FALSE);	
	
	AIMakeEntityDeaf('StealthTutTwo(hunter)', 0);
	AIMakeEntityBlind('StealthTutTwo(hunter)', 0);

	sleep(300);
	AIAddGoalForSubpack('leader(leader)', 'subStealthTut2', 'goalHideTwo');
	sleep(300);
	while AIIsGoalNameInSubpack('leader(leader)', 'subStealthTut2', 'goalHideTwo') do sleep(10);
	
	AISetEntityIdleOverride('StealthTutTwo(hunter)', TRUE, TRUE);
{	AIEntityPlayAnimLooped('StealthTutTwo(hunter)', 'BAT_LUNGING_INMATE_ANIM', 0.0);
	sleep(GetAnimationLength('BAT_LUNGING_INMATE_ANIM')-450);
	AIEntityCancelAnim('StealthTutTwo(hunter)', 'BAT_LUNGING_INMATE_ANIM');}
	AIEntityPlayAnimLooped('StealthTutTwo(hunter)', 'BAT_INMATE_IDLELOOP_CROUCHED_ANIM', 0.0);
	
	SetEntityInvulnerable(GetEntity('StealthTutTwo(hunter)'), TRUE);
	
end;

script OnLeaveTrigger;
begin
	{AIMakeEntityDeaf('StealthTutTwo(hunter)', 0);
	AIMakeEntityBlind('StealthTutTwo(hunter)', 0);}
	{RadarPositionSetEntity(GetEntity('StealthTutTwo(hunter)'), MAP_COLOR_YELLOW);}
	
	stealthTwoLooper := FALSE;
	RadarPositionSetEntity(GetEntity('StealthTutTwo(hunter)'), MAP_COLOR_YELLOW);
end;

end.

        ";

        $expected = [

'10000000',
'0a000000',
'11000000',
'0a000000',
'09000000',
'12000000',
'01000000',
'00000000',
'1a000000',
'01000000',
'c0170000',
'04000000',
'12000000',
'01000000',
'00000000',
'16000000',
'04000000',
'00010000',
'01000000',
'11000000',
'09000000',
'0a000000',
'0f000000',
'0a000000',
'3b000000',
'00000000',
'10000000',
'0a000000',
'11000000',
'0a000000',
'09000000',
'34000000',
'09000000',
'0c000000',
'12000000',
'01000000',
'01000000',
'1a000000',
'01000000',
'b4170000',
'04000000',
'1b000000',
'b4170000',
'04000000',
'01000000',
'10000000',
'01000000',
'12000000',
'01000000',
'01000000',
'0f000000',
'04000000',
'23000000',
'04000000',
'01000000',
'12000000',
'01000000',
'01000000',
'3f000000',
'fc000000',
'33000000',
'01000000',
'01000000',
'24000000',
'01000000',
'00000000',
'3f000000',
'54070000',
'ee020000',
'10000000',
'01000000',
'ef020000',
'0f000000',
'04000000',
'27000000',
'01000000',
'04000000',
'10000000',
'01000000',
'1b000000',
'c0170000',
'04000000',
'01000000',
'10000000',
'01000000',
'12000000',
'01000000',
'01000000',
'0f000000',
'04000000',
'23000000',
'04000000',
'01000000',
'12000000',
'01000000',
'01000000',
'3f000000',
'94010000',
'33000000',
'01000000',
'01000000',
'0f000000',
'04000000',
'27000000',
'01000000',
'04000000',
'10000000',
'01000000',
'89020000',
'29000000',
'01000000',
'01000000',
'10000000',
'01000000',
'21000000',
'04000000',
'01000000',
'00000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'77000000',
'10000000',
'01000000',
'8d030000',
'10000000',
'01000000',
'12000000',
'01000000',
'0000be42',
'10000000',
'01000000',
'4e000000',
'12000000',
'01000000',
'01000000',
'3d000000',
'44020000',
'33000000',
'01000000',
'01000000',
'0f000000',
'04000000',
'25000000',
'01000000',
'04000000',
'10000000',
'01000000',
'21000000',
'04000000',
'01000000',
'00000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'77000000',
'10000000',
'01000000',
'8d030000',
'10000000',
'01000000',
'12000000',
'01000000',
'0000aa42',
'10000000',
'01000000',
'4e000000',
'12000000',
'01000000',
'01000000',
'42000000',
'dc020000',
'33000000',
'01000000',
'01000000',
'0f000000',
'04000000',
'25000000',
'01000000',
'04000000',
'0f000000',
'04000000',
'27000000',
'01000000',
'04000000',
'24000000',
'01000000',
'00000000',
'3f000000',
'4c070000',
'14000000',
'01000000',
'04000000',
'00010000',
'10000000',
'01000000',
'12000000',
'01000000',
'00000000',
'0f000000',
'04000000',
'23000000',
'04000000',
'01000000',
'12000000',
'01000000',
'01000000',
'3f000000',
'70030000',
'33000000',
'01000000',
'01000000',
'24000000',
'01000000',
'00000000',
'3f000000',
'fc060000',
'12000000',
'01000000',
'2c010000',
'10000000',
'01000000',
'6a000000',
'21000000',
'04000000',
'01000000',
'00000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'21000000',
'04000000',
'01000000',
'18000000',
'12000000',
'02000000',
'09000000',
'10000000',
'01000000',
'10000000',
'02000000',
'e4000000',
'1b000000',
'cc170000',
'04000000',
'01000000',
'10000000',
'01000000',
'12000000',
'01000000',
'01000000',
'0f000000',
'04000000',
'23000000',
'04000000',
'01000000',
'12000000',
'01000000',
'01000000',
'40000000',
'50040000',
'33000000',
'01000000',
'01000000',
'24000000',
'01000000',
'00000000',
'3f000000',
'84040000',
'12000000',
'01000000',
'0a000000',
'10000000',
'01000000',
'6a000000',
'3c000000',
'f8030000',
'22000000',
'04000000',
'01000000',
'0c000000',
'10000000',
'01000000',
'12000000',
'01000000',
'62903f42',
'10000000',
'01000000',
'4f000000',
'32000000',
'09000000',
'04000000',
'10000000',
'01000000',
'12000000',
'01000000',
'00000000',
'10000000',
'01000000',
'4d000000',
'10000000',
'01000000',
'12000000',
'01000000',
'48e1ce41',
'10000000',
'01000000',
'84010000',
'21000000',
'04000000',
'01000000',
'00000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'77000000',
'10000000',
'01000000',
'22000000',
'04000000',
'01000000',
'0c000000',
'10000000',
'01000000',
'12000000',
'01000000',
'01000000',
'10000000',
'01000000',
'7d000000',
'21000000',
'04000000',
'01000000',
'00000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'77000000',
'10000000',
'01000000',
'12000000',
'01000000',
'5a000000',
'10000000',
'01000000',
'4d000000',
'10000000',
'01000000',
'b0020000',
'21000000',
'04000000',
'01000000',
'24000000',
'12000000',
'02000000',
'17000000',
'10000000',
'01000000',
'10000000',
'02000000',
'21000000',
'04000000',
'01000000',
'3c000000',
'12000000',
'02000000',
'0f000000',
'10000000',
'01000000',
'10000000',
'02000000',
'e4000000',
'21000000',
'04000000',
'01000000',
'00000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'21000000',
'04000000',
'01000000',
'4c000000',
'12000000',
'02000000',
'12000000',
'10000000',
'01000000',
'10000000',
'02000000',
'12000000',
'01000000',
'00000000',
'10000000',
'01000000',
'b4010000',
'21000000',
'04000000',
'01000000',
'00000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'77000000',
'10000000',
'01000000',
'12000000',
'01000000',
'01000000',
'10000000',
'01000000',
'5e010000',
'12000000',
'01000000',
'01000000',
'16000000',
'04000000',
'00010000',
'01000000',
'21000000',
'04000000',
'01000000',
'00000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'77000000',
'10000000',
'01000000',
'12000000',
'01000000',
'02000000',
'10000000',
'01000000',
'e0020000',
'3c000000',
'a4000000',
'21000000',
'04000000',
'01000000',
'00000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'77000000',
'10000000',
'01000000',
'e1020000',
'11000000',
'09000000',
'0a000000',
'0f000000',
'0a000000',
'3b000000',
'00000000',
'10000000',
'0a000000',
'11000000',
'0a000000',
'09000000',
'12000000',
'01000000',
'10270000',
'10000000',
'01000000',
'6a000000',
'21000000',
'04000000',
'01000000',
'60000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'21000000',
'04000000',
'01000000',
'78000000',
'12000000',
'02000000',
'12000000',
'10000000',
'01000000',
'10000000',
'02000000',
'17020000',
'21000000',
'04000000',
'01000000',
'60000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'12000000',
'01000000',
'00000000',
'10000000',
'01000000',
'12000000',
'01000000',
'00000000',
'10000000',
'01000000',
'b5010000',
'21000000',
'04000000',
'01000000',
'60000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'12000000',
'01000000',
'00000000',
'10000000',
'01000000',
'72010000',
'21000000',
'04000000',
'01000000',
'60000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'12000000',
'01000000',
'00000000',
'10000000',
'01000000',
'71010000',
'12000000',
'01000000',
'2c010000',
'10000000',
'01000000',
'6a000000',
'21000000',
'04000000',
'01000000',
'8c000000',
'12000000',
'02000000',
'0f000000',
'10000000',
'01000000',
'10000000',
'02000000',
'21000000',
'04000000',
'01000000',
'9c000000',
'12000000',
'02000000',
'0f000000',
'10000000',
'01000000',
'10000000',
'02000000',
'21000000',
'04000000',
'01000000',
'ac000000',
'12000000',
'02000000',
'0c000000',
'10000000',
'01000000',
'10000000',
'02000000',
'56010000',
'12000000',
'01000000',
'2c010000',
'10000000',
'01000000',
'6a000000',
'21000000',
'04000000',
'01000000',
'8c000000',
'12000000',
'02000000',
'0f000000',
'10000000',
'01000000',
'10000000',
'02000000',
'21000000',
'04000000',
'01000000',
'9c000000',
'12000000',
'02000000',
'0f000000',
'10000000',
'01000000',
'10000000',
'02000000',
'21000000',
'04000000',
'01000000',
'ac000000',
'12000000',
'02000000',
'0c000000',
'10000000',
'01000000',
'10000000',
'02000000',
'a5020000',
'24000000',
'01000000',
'00000000',
'3f000000',
'880a0000',
'12000000',
'01000000',
'0a000000',
'10000000',
'01000000',
'6a000000',
'3c000000',
'cc090000',
'21000000',
'04000000',
'01000000',
'60000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'12000000',
'01000000',
'01000000',
'10000000',
'01000000',
'12000000',
'01000000',
'01000000',
'10000000',
'01000000',
'b5010000',
'21000000',
'04000000',
'01000000',
'60000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'21000000',
'04000000',
'01000000',
'bc000000',
'12000000',
'02000000',
'22000000',
'10000000',
'01000000',
'10000000',
'02000000',
'12000000',
'01000000',
'00000000',
'10000000',
'01000000',
'b4010000',
'21000000',
'04000000',
'01000000',
'60000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'77000000',
'10000000',
'01000000',
'12000000',
'01000000',
'01000000',
'10000000',
'01000000',
'5e010000',
'11000000',
'09000000',
'0a000000',
'0f000000',
'0a000000',
'3b000000',
'00000000',
'10000000',
'0a000000',
'11000000',
'0a000000',
'09000000',
'12000000',
'01000000',
'00000000',
'1a000000',
'01000000',
'b4170000',
'04000000',
'21000000',
'04000000',
'01000000',
'e0000000',
'12000000',
'02000000',
'16000000',
'10000000',
'01000000',
'10000000',
'02000000',
'77000000',
'10000000',
'01000000',
'12000000',
'01000000',
'04000000',
'10000000',
'01000000',
'e0020000',
'11000000',
'09000000',
'0a000000',
'0f000000',
'0a000000',
'3b000000',
'00000000',


        ];


        $compiler = new Compiler();
        $levelScriptCompiled = $compiler->parse(file_get_contents(__DIR__ . '/0#levelscript.srce'));

        $compiler = new Compiler();
        $compiled = $compiler->parse($script, $levelScriptCompiled);

        if ($compiled['CODE'] != $expected){
            foreach ($compiled['CODE'] as $index => $item) {
                if ($expected[$index] == $item){
                    echo ($index + 1) . '->' . $item . "\n";
                }else{
                    echo "MISSMATCH need " . $expected[$index] . " got " . $compiled['CODE'][$index] . "\n";
                }
            }
            exit;
        }

        $this->assertEquals($compiled['CODE'], $expected, 'The bytecode is not correct');
    }


}