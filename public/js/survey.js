let questions = [];
let currentStep = 1; // Tracks the current question
let questionId = null; // Store question_id from the backend
let userAnswers = {}; // Initialize to store user's answers

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

// Navigate to the previous question
function prevQuestion() {
    if (currentStep > 1) {
        currentStep--;
        showQuestion(currentStep);
    }
}

// Navigate to the next question
function nextQuestion() {
    const currentQuestion = questions[currentStep - 1];
    console.log('Navigating to the next question:', currentQuestion); // Debugging

    // Get the selected answer
    const selectedOption = document.querySelector(`input[name="question_${currentQuestion.id}"]:checked`);

    // Validation: Ensure the user selects an answer
    if (!selectedOption) {
        alert('Please select an answer before proceeding.');
        return;
    }

    // Save the answer
    saveAnswer(currentQuestion.id, selectedOption.value);

    // Conditional navigation
    if (typeof currentQuestion.next === 'object') {
        const nextStep = currentQuestion.next[selectedOption.value];
        if (nextStep) {
            currentStep = questions.findIndex(q => q.id === nextStep) + 1;
        } else {
            alert('Invalid next question mapping.');
        }
    } else {
        currentStep++;
    }

    showQuestion(currentStep);
}

// Submit survey responses
function submitSurvey() {
    console.log('Submitting survey...'); // Debugging
    console.log('User answers:', userAnswers); // Debugging

    // Ensure all questions have answers
    for (const question of questions) {
        if (!userAnswers[question.id]) {
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
