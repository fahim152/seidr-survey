@extends('layouts.app')

@section('title', 'Question')

@section('content')

<style>
.form-container{
    flex-direction: column;
    justify-content: space-between;
    align-items: stretch;
    height: 50vh;
    padding: 5% 30% 5% 10%;
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




label {
    font-weight: bold;
}


.form-top-part{
    display:flex;
    flex-direction:column;
    gap:36px;
}

.image-radio-group {
  display: flex;
  gap: 10px;
}

.image-radio {
  position: relative;
  cursor: pointer;
}

.image-radio input[type="radio"] {
  display: none; /* Hide the default radio buttons */
}

.image-radio img {
  border: 2px solid transparent;
  border-radius: 13px;
 
}

.image-radio input[type="radio"]:checked + img {
  border: 3px solid  #50b376; /* Highlight color for selected image */
  transform: scale(1.05); /* Slightly enlarge the selected image */
}

.image-radio img:hover {
  border-color: #ccc; /* Highlight on hover */
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

<!-- --------------------Question 1----------------------------- -->
<div class="form-container">

 <div class="form-top-part">
    <label class="top-text">Current stage of your company ?</label>
    

     <div class="image-radio-group">
      <label class="image-radio">
       <input type="radio" name="option" value="Startup">
       <img src="/Images/startup.png" alt="startup">
      </label>

  <label class="image-radio">
    <input type="radio" name="option" value="growth">
    <img src="/Images/growth.png" alt="growth">
  </label>

  <label class="image-radio">
    <input type="radio" name="option" value="established">
    <img src="/Images/established.png" alt="established">
  </label>

</div>
</div>
 <div class="button-container">
   <button  class="previous-button">Previous</button>
   <button  class="next-button">Next</button>
  </div>
</div>

<!-- ------------------------Question 2--------------------------------- -->

<div class="form-container">

 <div class="form-top-part">
    <label class="top-text">What are you selling ?</label>
    

     <div class="image-radio-group">
      <label class="image-radio">
       <input type="radio" name="option" value="service">
       <img src="/Images/service.png" alt="service">
      </label>

  <label class="image-radio">
    <input type="radio" name="option" value="product">
    <img src="/Images/product.png" alt="product">
  </label>


</div>
</div>
 <div class="button-container">
   <button  class="previous-button">Previous</button>
   <button  class="next-button">Next</button>
  </div>
</div>




<!-- ------------------------Question 3--------------------------------- -->

<div class="form-container">

 <div class="form-top-part">
    <label class="top-text">What type of company are you ?</label>
    

     <div class="image-radio-group">
      <label class="image-radio">
       <input type="radio" name="option" value="B2C">
       <img src="/Images/b2c.png" alt="B2C">
      </label>

  <label class="image-radio">
    <input type="radio" name="option" value="B2B">
    <img src="/Images/b2b.png" alt="B2B">
  </label>


</div>
</div>
 <div class="button-container">
   <button  class="previous-button">Previous</button>
   <button  class="next-button">Next</button>
  </div>
</div>


<!-- ------------------------Question 4--------------------------------- -->

<div class="form-container">

 <div class="form-top-part">
    <label class="top-text">How many are you in the company ?</label>
    

     <div class="image-radio-group">
      <label class="image-radio">
       <input type="radio" name="option" value="1-3">
       <img src="/Images/1-3.png" alt="1-3">
      </label>

  <label class="image-radio">
    <input type="radio" name="option" value="3-10">
    <img src="/Images/3-10.png" alt="3-10">
  </label>

  <label class="image-radio">
    <input type="radio" name="option" value="10">
    <img src="/Images/10.png" alt="10">
  </label>
 </div>

</div>
 <div class="button-container">
   <button  class="previous-button">Previous</button>
   <button  class="next-button">Next</button>
  </div>
</div>


<!-- ------------------------Question 5--------------------------------- -->

<div class="form-container">

 <div class="form-top-part">
    <label class="top-text">Do you have a website ?</label>
    

     <div class="image-radio-group">
      <label class="image-radio">
       <input type="radio" name="option" value="yes">
       <img src="/Images/yes.png" alt="yes">
      </label>

  <label class="image-radio">
    <input type="radio" name="option" value="no">
    <img src="/Images/no.png" alt="no">
  </label>
</div>

</div>
 <div class="button-container">
   <button  class="previous-button">Previous</button>
   <button  class="next-button">Next</button>
  </div>
</div>

<!-- ------------------------Question 5 IF NO--------------------------------- -->

<div class="form-container">

 <div class="form-top-part">
    <label class="top-text">Would you like a website ?</label>
    

     <div class="image-radio-group">
      <label class="image-radio">
       <input type="radio" name="option" value="yes">
       <img src="/Images/yes.png" alt="yes">
      </label>

  <label class="image-radio">
    <input type="radio" name="option" value="no">
    <img src="/Images/no.png" alt="no">
  </label>
  </div>


</div>
 <div class="button-container">
   <button  class="previous-button">Previous</button>
   <button  class="next-button">Next</button>
  </div>
</div>



<!-- ------------------------Question 5 IF YES --------------------------------- -->

<div class="form-container">

 <div class="form-top-part">
    <label class="top-text">Would you like a website ?</label>
    

     <div class="image-radio-group">
      <label class="image-radio">
       <input type="radio" name="option" value="yes">
       <img src="/Images/yes.png" alt="yes">
      </label>

  <label class="image-radio">
    <input type="radio" name="option" value="no">
    <img src="/Images/no.png" alt="no">
  </label>
  </div>


</div>
 <div class="button-container">
   <button  class="previous-button">Previous</button>
   <button  class="next-button">Next</button>
  </div>
</div>


<!-- ------------------------Question 5 IF YES --------------------------------- -->

<div class="form-container">

 <div class="form-top-part">
    <label class="top-text">Who should manage your site: SEIDR or by yourself</label>
    

     <div class="image-radio-group">
      <label class="image-radio">
       <input type="radio" name="option" value="I can myself">
       <img src="/Images/myself.png" alt="yes">
      </label>

  <label class="image-radio">
    <input type="radio" name="option" value="Guidance">
    <img src="/Images/guidance.png" alt="no">
  </label>

  <label class="image-radio">
    <input type="radio" name="option" value="Hands of the wheel">
    <img src="/Images/wheel.png" alt="no">
  </label>

  </div>


</div>
 <div class="button-container">
   <button  class="previous-button">Previous</button>
   <button  class="next-button">Next</button>
  </div>
</div>

 
@endsection
