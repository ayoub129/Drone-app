const form = document.getElementById("form");
const email = document.getElementById("Email");
const pass = document.getElementById("Password");

// show the error
const showError = (input, msg) => {
  const formcontrol = input.parentElement;
  formcontrol.className = "form-control error mb-2 pb-6";
  const small = formcontrol.querySelector("small");
  small.innerText = msg;
};

// show the success
const showSuccess = (input) => {
  const formcontrol = input.parentElement;
  formcontrol.className = "form-control success mb-2 pb-6";
};

// check if the email valid
let erremail = null;
const isValidEmail = (input) => {
  const re =
    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  if (re.test(input.value.trim())) {
    showSuccess(input);
    erremail = null
  } else {
    showError(input, "Email Is Not Valid");
    erremail = 'error'
  }
};

// check required field
let errrequired = null;
function checkRequired(inputArr) {
  inputArr.forEach((input) => {
    if (input.value.trim() === "") {
      showError(input, `${getFieldName(input)} is Required`); 
      errrequired = 'error';
    } else {
      showSuccess(input);
      errrequired = null;
    }
  });
}

// check input length
let errlength = null
function checkLength(input, min, max) {
  if (input.value.length < min) {
    showError(
      input,
      `${getFieldName(input)} must be at least ${min} Characters`
    );
    errlength = 'error' 
  } else if (input.value.length > max) {
    showError(
      input,
      `${getFieldName(input)} must be less than ${max} Characters`
    ); 
    errlength = 'error'
  } else {
    showSuccess(input);
    errlength = null
  }
}

// get Field Name
function getFieldName(input) {
  return input.id.charAt(0).toUpperCase() + input.id.slice(1);
}

form.addEventListener("submit", (e) => {
  e.preventDefault();
  checkRequired([email, pass]);
  checkLength(pass, 6, 25);
  isValidEmail(email);

  if(errlength == null && erremail == null && errrequired == null) {


    async function postData(url = "", data = {}) {
      const response = await fetch(url, {
        method: "POST", 
        mode: "cors", 
        cache: "no-cache", 
        credentials: "same-origin", 
        headers: {
          "Content-Type": "application/json",
        },
        redirect: "follow", 
        referrerPolicy: "no-referrer",
        body: JSON.stringify(data), 
      });
      return response.json(); // parses JSON response into native JavaScript objects
    }

    postData("login-api.php", { Email: email.value, password: pass.value }).then((data) => {
      if(data.data == 'no user') {
        showError(email , '')
        showError(pass , 'Email Or Password Is uncorecct')
      } else {
        const id = data.data.id
        console.log(id)
        window.location.replace("http://localhost/drone-app/home.php");
      }
    });

  } else {
    console.log('Errooooooooooooooooooorre')
  }
});