let questions = [];
let currentStep = 1; // Tracks the current question
let questionId = null; // Store question_id from the backend
let userAnswers = {}; // Initialize to store user's answers
let displayedQuestions = []; // Track questions shown to the user

// Fetch questions from the backend
fetch('/api/questions')
    .then((response) => response.json())
    .then((data) => {
        if (data.error) {
            alert(data.error);
            return;
        }
        questionId = data.id; // Save the question_id for the survey
        questions = data.questions;
        console.log('Questions fetched:', questions); // Debugging
        showQuestion(currentStep); // Show the first question
    })
    .catch((err) => console.error('Failed to fetch questions:', err));

// Display the current question
function showQuestion(step) {
    console.log('Current step:', step); // Debugging
    const container = document.getElementById('question-container');
    container.innerHTML = ''; // Clear previous content

    const question = questions[step - 1];
    if (!question) {
        console.warn('No question found for step:', step);
        return;
    }

    console.log('Current question:', question); // Debugging

    // Track the question being displayed
    if (!displayedQuestions.includes(question.id)) {
        displayedQuestions.push(question.id);
    }

    // Question Header
    const questionHeader = document.createElement('div');
    questionHeader.className = 'form-top-part';

    const questionLabel = document.createElement('label');
    questionLabel.className = 'top-text';
    questionLabel.textContent = question.question;
    questionHeader.appendChild(questionLabel);

    container.appendChild(questionHeader);

    // Options (e.g., radio buttons)
    if (question.type === 'radio') {
        const optionsContainer = document.createElement('div');
        optionsContainer.className = 'image-radio-group';

        question.options.forEach((option) => {
            const label = document.createElement('label');
            label.className = 'image-radio';

            const input = document.createElement('input');
            input.type = 'radio';
            input.name = `question_${question.id}`;
            input.value = option;

            // Pre-select previously saved answers
            if (userAnswers[question.id] === option) {
                input.checked = true;
            }

            input.onchange = () => saveAnswer(question.id, option);

            const img = document.createElement('img');
            img.src = `/Images/${option.toLowerCase()}.png`; // Adjust this if image names don't match options
            img.alt = option;

            label.appendChild(input);
            label.appendChild(img);
            optionsContainer.appendChild(label);
        });

        container.appendChild(optionsContainer);
    }

    // Show/Hide Navigation Buttons
    document.getElementById('prev-btn').style.display = step > 1 ? 'inline-block' : 'none';
    document.getElementById('next-btn').style.display = question.next ? 'inline-block' : 'none';
    document.getElementById('submit-btn').style.display = question.next ? 'none' : 'inline-block';
}

// Save the user's answer
function saveAnswer(questionId, answer) {
    console.log(`Answer saved for question ${questionId}:`, answer); // Debugging
    userAnswers[questionId] = answer;
}

// Navigate to the next question
function nextQuestion() {
    const currentQuestion = questions[currentStep - 1];
    console.log('Navigating to the next question:', currentQuestion); // Debugging

    // Get the selected answer for the current question
    const selectedOption = document.querySelector(`input[name="question_${currentQuestion.id}"]:checked`);

    // Validate only the current question (visible on screen)
    if (!selectedOption) {
        alert('Please select an answer before proceeding.');
        return;
    }

    // Save the answer for the current question
    saveAnswer(currentQuestion.id, selectedOption.value);

    // Determine the next step based on the `next` field in the current question
    let nextStep;
    if (typeof currentQuestion.next === 'object') {
        nextStep = currentQuestion.next[selectedOption.value]; // Conditional navigation
    } else {
        nextStep = currentQuestion.next; // Default next step
    }

    // Check if the next step is `null`, indicating the survey should end
    if (nextStep === null) {
        console.log('Survey ended early. Submitting answers...'); // Debugging
        submitSurvey(); // Automatically submit the survey
        return;
    }

    // Validate the next step (ensure it's a valid question ID)
    if (nextStep) {
        const nextStepIndex = questions.findIndex(q => q.id === nextStep);
        if (nextStepIndex === -1) {
            alert('Invalid navigation detected. Please check the question structure.');
            return;
        }
        currentStep = nextStepIndex + 1; // Move to the next question based on ID
    } else {
        currentStep++; // Fallback: Proceed to the next question in the sequence
    }

    showQuestion(currentStep); // Display the next question
}


// Submit survey responses
function submitSurvey() {
    console.log('Submitting survey...'); // Debugging
    console.log('User answers:', userAnswers); // Debugging

    // Validate only the displayed questions
    for (const questionId of displayedQuestions) {
        if (!userAnswers[questionId]) {
            const question = questions.find(q => q.id === questionId);
            alert(`Please answer the question: ${question.question}`);
            return;
        }
    }

    // Submit answers to the backend
    fetch('/submit-survey', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            answers: userAnswers, // User's answers
            question_id: questionId, // Include the question set ID
        }),
    })
        .then((response) => {
            if (response.ok) {
                window.location.href = '/thank-you';
            } else {
                alert('Failed to submit survey. Please try again.');
            }
        })
        .catch((err) => console.error('Error submitting survey:', err));
}
