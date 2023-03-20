function previewImage() {
  const preview = document.getElementById("image-preview");
  const preview2 = document.getElementById("image-preview2");
  const file = document.querySelector("input[type=file]").files[0];
  const reader = new FileReader();

  if (file) {
    reader.addEventListener(
      "load",
      function () {
        preview.src = reader.result;
      },
      false
    );

    reader.readAsDataURL(file);
    preview.style.display = "block";
    preview2.style.display = "none";
  } else {
    preview.style.display = "none";
  }
}

const fileInput = document.getElementById("fileInput");
if (fileInput) {
  fileInput.addEventListener("change", previewImage);
}

/* tinymce */

tinymce.init({
  selector: "#story",
  plugins:
    "anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss",
  toolbar:
    "undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat",
  tinycomments_mode: "embedded",
  tinycomments_author: "Author name",
  mergetags_list: [
    { value: "First.Name", title: "First Name" },
    { value: "Email", title: "Email" },
  ],
});

/* delete article */

const deleteBtn = document.getElementById("delete-btn");

deleteBtn.addEventListener("click", (e) => {
  e.preventDefault();

  const confirmationMessage =
    "Êtes-vous sûr de vouloir supprimer cet article ?";

  const userConfirmation = confirm(confirmationMessage);

  if (userConfirmation) {
    document.getElementById("delete-form").submit();
  }
});
