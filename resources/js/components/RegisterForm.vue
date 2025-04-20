<template>
  <div class="p-4" :dir="currentLanguage === 'ur' ? 'rtl' : 'ltr'">
    <div class="language-buttons mb-4 text-center">
      <button @click="switchLanguage('en')" :class="{ active: currentLanguage === 'en' }" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
        English
      </button>
      <button @click="switchLanguage('ur')" :class="{ active: currentLanguage === 'ur' }" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
        اردو
      </button>
    </div>

    <form @submit.prevent="submitForm">
      <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>

      <div class="form-group">
        <label for="first_name">{{ translations.first_name || 'First Name' }}</label>
        <input type="text" id="first_name" v-model="form.first_name" required />
        <div v-if="errors.first_name" class="error-message">{{ errors.first_name }}</div>
      </div>

      <div class="form-group">
        <label for="last_name">{{ translations.last_name || 'Last Name' }}</label>
        <input type="text" id="last_name" v-model="form.last_name" required />
        <div v-if="errors.last_name" class="error-message">{{ errors.last_name }}</div>
      </div>

      <div class="form-group">
        <label for="email">{{ translations.email || 'Email' }}</label>
        <input type="email" id="email" v-model="form.email" required />
        <div v-if="errors.email" class="error-message">{{ errors.email }}</div>
      </div>

      <div class="form-group">
        <label for="username">{{ translations.username || 'Username' }}</label>
        <input type="text" id="username" v-model="form.username" required />
        <div v-if="errors.username" class="error-message">{{ errors.username }}</div>
      </div>

      <div class="form-group">
        <label for="password">{{ translations.password || 'Password' }}</label>
        <input type="password" id="password" v-model="form.password" required />
        <div v-if="errors.password" class="error-message">{{ errors.password }}</div>
      </div>

      <button type="submit">{{ translations.register || 'Register' }}</button>
    </form>
  </div>
</template>


<script>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default {
  setup() {
    const form = ref({
      first_name: "",
      last_name: "",
      email: "",
      username: "",
      password: "",
      password_confirmation: "",
    });
    const errors = ref({});
    const successMessage = ref("");
    const translations = ref({});
    const currentLanguage = ref('en'); // Default to English
    const router = useRouter();

    const fetchTranslations = async (locale) => {
      try {
        const response = await axios.get(`/translations/${locale}`);
        translations.value = response.data;
        currentLanguage.value = locale; // Update current language
      } catch (error) {
        console.error("Error fetching translations:", error);
      }
    };

    const switchLanguage = async (lang) => {
  errors.value = {}; // Clear existing errors
  await fetchTranslations(lang); // Fetch translations for the new language
  localStorage.setItem('preferredLanguage', lang);

  // Optionally, re-validate the form if there were previous errors
  if (Object.keys(errors.value).length > 0) {
    await submitForm();
  }
};

    const submitForm = async () => {
  errors.value = {}; // Reset errors
  try {
    const response = await axios.post("/api/register", form.value, {
      headers: {
        'Accept-Language': currentLanguage.value // Send the current language to the backend
      }
    });
    
    if (response.data.success && response.data.user_id) {
      successMessage.value = translations.value.registration_success || "Registration successful!";
      window.location.href = `/country/${response.data.user_id}`;
    } else {
      errors.value.registration_failed = translations.value.registration_failed || "Failed to register user.";
    }
  } catch (error) {
    if (error.response?.data?.errors) {
      // Handle validation errors and map them to the respective fields
      errors.value = error.response.data.errors;
    } else {
      errors.value.unexpected_error = error.response?.data?.message || translations.value.unexpected_error || "Unexpected error occurred.";
    }
    console.error("Registration error:", error);
  }
};
    // Fetch initial translations
    const storedLanguage = localStorage.getItem('preferredLanguage') || 'en';
    fetchTranslations(storedLanguage); // Fetch translations for the stored language

    return { form, errors, successMessage, translations, submitForm, switchLanguage, currentLanguage };
  },
};
</script>
