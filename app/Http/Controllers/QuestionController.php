<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kind;
use App\Models\Speed;

class QuestionController extends Controller
{
    public function index(Request $request){
        $kind = Kind::find($request->kind_id);
        $speed = Speed::find($request->speed_id);
        $questions = [];
        $answers = [];
        while(true){
            $questionId = $this->questionId();
            $questionDigits = $this->getQuestionDigits($kind->sign);
            $digit1 = $questionDigits['digit1'];
            $digit2 = $questionDigits['digit2'];
            $qAnswer = $questionDigits['answer'];
            if(!$this->isExists($digit1, $digit2, $questions)){
                $question = [
                    'question_id' => $questionId,
                    'digit1' => $digit1,
                    'digit2' => $digit2,
                    'is_done' => false,
                    'answer' => $qAnswer,
                ];
                $answer = [
                    'question_id' => $questionId,
                    'answer' => $qAnswer
                ];
                array_push($questions, $question);
                array_push($answers, $answer);
                if(count($questions) == 25){
                    break;
                }
            }
        }
        shuffle($questions);
        shuffle($answers);
        $combos = $this->winCombos();
        $data = [
            'questions' => $questions,
            'answers' => $answers,
            'sign' => $kind->sign,
            'time' => $speed->time,
            'combos' => $combos,
        ];
        return $data;
    }
}
