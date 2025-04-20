<template>
  <div class="p-4" :dir="currentLanguage === 'ur' ? 'rtl' : 'ltr'">
    <div class="language-buttons mb-4 text-center bg-black">
      <button @click="switchLanguage('en')" :class="{ active: currentLanguage === 'en' }" class="px-4 ">
        English
      </button>
      <button @click="switchLanguage('ur')" :class="{ active: currentLanguage === 'ur' }" class="px-4 py-2 ">
        اردو
      </button>
    </div>

    <form @submit.prevent="submitForm">
      <div v-if="error" class="alert alert-danger">{{ error }}</div>
      <label for="country">{{ translations.select_country || 'Select Country:' }}</label>
      <select v-model="selectedCountry" required>
        <option value="" disabled>{{ translations.select_country_placeholder || 'Select a Country' }}</option>
        <option v-for="country in countries" :key="country.id" :value="country.name">{{ country.name }}</option>
      </select>
      <button type="submit" :disabled="!selectedCountry">{{ translations.next || 'Next' }}</button>
    </form>
  </div>
</template>

<script>
import { ref } from "vue";
import axios from "axios";

export default {
    props: {
        userId: {
            type: Number,
            required: true
        },
        countries: {
            type: Array,
            required: true
        }
      },
  setup(props) {
    const selectedCountry = ref("");
    const countries = ref([]);
    const error = ref("");
    const translations = ref({});
    const currentLanguage = ref('en'); // Default to English

    const fetchCountries = async () => {
      try {
        const response = await axios.get("/countries");
        countries.value = response.data;
      } catch (err) {
        error.value = "Failed to load countries.";
      }
    };

    const fetchTranslations = async (locale) => {
      try {
        const response = await axios.get(`/translations/${locale}`);
        translations.value = response.data;
        currentLanguage.value = locale; // Update current language
      } catch (error) {
        console.error("Error fetching translations:", error);
      }
    };

    const switchLanguage = (lang) => {
      fetchTranslations(lang);
      localStorage.setItem('preferredLanguage', lang); // Store the selected language in local storage
    };

    const submitForm = async () => {
      try {
        const response = await axios.post("/store-country", {
          country: selectedCountry.value,
          user_id: props.userId,
        });
        if (response.data.success) {
          window.location.href = `/state/${response.data.country_id}/${props.userId}`;
        }
      } catch (err) {
        error.value = translations.value.store_country_failed || "Failed to store country.";
      }
    };

    // Fetch initial data
    fetchCountries();
    const storedLanguage = localStorage.getItem('preferredLanguage') || 'en';
    fetchTranslations(storedLanguage); // Fetch translations for the stored language

    return { selectedCountry, countries, error, translations, submitForm, switchLanguage, currentLanguage };
  },
};
</script>

<style>
/* Add any necessary styles */
</style>
