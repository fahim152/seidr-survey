@extends('layouts.app')

@section('title', 'Question')

@section('content')

<style>
.form-container{
    flex-direction: column;
    justify-content: space-between;
    align-items: stretch;
    height: 50vh;
    padding: 15% 30% 5% 10%;
    display: flex;  
    color:white;
}

.top-text{
  font-size: 3rem ;
  font-weight: bold;
  line-height: 48px;
  
}
.button-container{
  justify-content: space-between;
  align-self: flex-start;
  align-items: flex-end;
  display: flex;
  gap:24px
}

.next-button{
  background: transparent;
  border: none;
  font-size: 2rem; 
  font-weight: bold;
  display:block;
  height: 30px;
  color: white; 
}

.previous-button{
  background: transparent;
  border: none;
  font-size: 1rem;
  font-weight: normal;
  color: #848c8e;
  display:block;
  height: 15px;
  
}

.radio-form{
    display: flex;

}

input{
  color: #848c8e;
  background-color: #0000;
  border: 1px #000;
  border-bottom: 2px solid #fff;
  width: 80%;
  height: 60px;
  padding-bottom: 10px;
  font-size: 28px;
  line-height: 28px;
}

button{
cursor: pointer;
}

input:focus {
    outline: none;
}

.radio-button-field {
    align-items: center;
    margin-right: 50px;
    display: flex;
}

.w-radio {
    margin-bottom: 5px;
 
}

label {
    font-weight: bold;
}



.w-form-label {
  cursor: pointer;
  margin-bottom: 0;
  font-weight: normal;
  display: inline-block;
}

.form-radio-container {
    justify-content: flex-start;
    align-items: start;
    display: flex;
    margin-top:24px
}
.radio-btn {
  width: 30px;
  height: 30px;
  margin-right: 10px;
}

.form-top-part{
    display:flex;
    flex-direction:column;
    gap:36px;
}
</style>


<!-- <div class="form-container">
    <h3>{{ $question['question'] }}</h3>
     <form method="POST" action="{{ url('questions/'.$step) }}">
        @csrf
        @if ($question['type'] === 'text')
            <input type="text" name="answer" class="form-control mb-3" required>
        @elseif ($question['type'] === 'number')
            <input type="number" name="answer" class="form-control mb-3" required>
        @endif

        <div class="button-container">
         <button  class="previous-button">Previous</button>
         <button  class="next-button">Next</button>
        </div>
    </form>
</div> -->


<div class="form-container">

 <div class="form-top-part">
    <label class="top-text">Current stage of your company</label>
    
     <div class="form-radio-container">
      <label class="radio-button-field w-radio"><input id="2500" name="Copmpany_type" data-name="Copmpany_type" type="radio" class="w-form-formradioinput radio-btn w-radio-input" value="Just Starting"><span class="radio-label w-form-label">Just Starting</span></label>
      <label class="radio-button-field w-radio"><input id="2500" name="Copmpany_type" data-name="Copmpany_type" type="radio" class="w-form-formradioinput radio-btn w-radio-input" value="Growth"><span class="radio-label w-form-label">Growth</span></label>
      <label class="radio-button-field w-radio"><input id="2500" name="Copmpany_type" data-name="Copmpany_type" type="radio" class="w-form-formradioinput radio-btn w-radio-input" value="Established"><span class="radio-label w-form-label">Established</span></label>
     </div>
</div>
 <div class="button-container">
   <button  class="previous-button">Previous</button>
   <button  class="next-button">Next</button>
  </div>
</div>

 
@endsection
