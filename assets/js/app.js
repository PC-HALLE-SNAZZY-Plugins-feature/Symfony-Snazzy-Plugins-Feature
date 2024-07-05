

document.addEventListener('DOMContentLoaded', () => {
    // Function to handle adding new fields to a collection
    const handleAddField = (collectionHolder, addButtonClass, addButtonText, fieldType) => {
        let addButton = document.createElement('button');
        addButton.type = 'button';
        addButton.classList.add(
            addButtonClass,
            'w-full',
            'px-4',
            'py-2',
            'rounded',
            'bg-blue-600',
            'text-white',
            'hover:bg-blue-700',
            'focus:outline-none',
            'transition-colors',
            'my-2'
        );
        addButton.innerText = addButtonText;

        let newLinkLi = document.createElement('div');
        newLinkLi.appendChild(addButton);

        collectionHolder.appendChild(newLinkLi);

        let addFormToCollection = (e) => {
            let prototype = collectionHolder.dataset.prototype;
            let index = collectionHolder.dataset.index;
            let newForm = prototype.replace(/__name__/g, index);
            collectionHolder.dataset.index = parseInt(index) + 1;

            let newFormLi = document.createElement('div');
            newFormLi.innerHTML = newForm;
            newLinkLi.parentNode.insertBefore(newFormLi, newLinkLi);
        };

        addButton.addEventListener('click', addFormToCollection);

        collectionHolder.dataset.index = collectionHolder.querySelectorAll('input').length;
    };

    // ScreenShots Collection
    let screenShotsCollectionHolder = document.querySelector('.screenShots-collection');
    if (screenShotsCollectionHolder) {
        handleAddField(screenShotsCollectionHolder, 'add_screenShot_link', 'Add a screen shot', 'screenshot');
    }
  // Credentials Form Fields Collection
  let credentialsCollectionHolder = document.querySelector('.credentials-form-fields-collection');
  if (credentialsCollectionHolder) {
      handleAddField(credentialsCollectionHolder, 'add_credentials_form_field_link', 'Add a credential field', 'credential');
  }

});


function handleDescriptionContent() {
    let moreDescription = document.getElementById('more-plugin-description');
    let lessDescription = document.getElementById('less-plugin-description');

    if (moreDescription.classList.contains('hidden')) {
        moreDescription.classList.remove('hidden');
        lessDescription.classList.add('hidden');
    } else {
        moreDescription.classList.add('hidden');
        lessDescription.classList.remove('hidden');
    }
}
function handleSetUpContent() {
    let moreSetup = document.getElementById('more-plugin-setup');
    let lessSetup = document.getElementById('less-plugin-setup');

    if (moreSetup.classList.contains('hidden')) {
        moreSetup.classList.remove('hidden');
        lessSetup.classList.add('hidden');
    } else {
        moreSetup.classList.add('hidden');
        lessSetup.classList.remove('hidden');
    }
}