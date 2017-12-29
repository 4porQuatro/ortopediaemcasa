<?php
  namespace App\Http\Controllers;

  use Illuminate\Http\Request;

  class FaqsController extends Controller{

    public function index(){
        $questions = $this->getQuestion();

        return view('front.pages.faqs.index', compact(
          'questions'
        ));
    }

    public function getQuestion(){
        $question = new \stdClass;

        $question->question = 'Questão';
        $question->answer = '<p>Resposta</p>';

        $questions = [];

        for($i = 0; $i < 4; $i++){
          $questions[] = $question;
        }

        return $questions;
    }

  }
?>
