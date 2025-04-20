<template>
  <div class="p-4" :dir="currentLanguage === 'ur' ? 'rtl' : 'ltr'">
    <div class="language-buttons mb-4 text-center bg-black">
      <button @click="switchLanguage('en')" :class="{ active: currentLanguage === 'en' }" class="px-4">
        English
      </button>
      <button @click="switchLanguage('ur')" :class="{ active: currentLanguage === 'ur' }" class="px-4 py-2">
        اردو
      </button>
    </div>

    <form @submit.prevent="goToDetailForm">
      <div v-if="error" class="alert alert-danger">{{ error }}</div>

      <div class="form-group">
        <label for="city">{{ translations.select_city || 'Select City:' }}</label>
        <select id="city" v-model="selectedCity" required>
          <option value="" disabled>{{ translations.select_city_placeholder || 'Select your city' }}</option>
          <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
        </select>
      </div>

      <button type="submit" :disabled="!selectedCity">{{ translations.next || 'Next' }}</button>
    </form>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";

export default {
  props: {
    stateId: {
      type: Number,
      required: true,
    },
    countryId: {
      type: Number,
      required: true,
    },
    userId: {
      type: Number,
      required: true,
    },
  },
  setup(props) {
    const selectedCity = ref(null);
    const cities = ref([]);
    const error = ref("");
    const translations = ref({});
    const currentLanguage = ref('en'); // Default to English

    // Fetch cities based on state_id
    const fetchCities = async () => {
      try {
        const response = await axios.get(`/cities/${props.stateId}`);
        cities.value = response.data;
      } catch (err) {
        error.value = translations.value.error_load_cities || "Failed to load cities. Please try again later.";
      }
    };

    // Fetch translations for the selected language
    const fetchTranslations = async (locale) => {
      try {
        const response = await axios.get(`/translations/${locale}`);
        translations.value = response.data;
        currentLanguage.value = locale; // Update current language
      } catch (err) {
        console.error("Error fetching translations:", err);
      }
    };

    // Switch language and update translations
    const switchLanguage = (lang) => {
      fetchTranslations(lang);
      localStorage.setItem('preferredLanguage', lang); // Store the selected language in local storage
    };

    // Redirect to the DetailForm page with the selected parameters
    const goToDetailForm = () => {
      if (!selectedCity.value) {
        error.value = translations.value.select_city_required || "Please select a city.";
        return;
      }

      // Use replace instead of push to replace the current route
      window.location.href = `/detail/${selectedCity.value}/${props.stateId}/${props.countryId}/${props.userId}`;
    };

    // Fetch initial data
    onMounted(async () => {
      const storedLanguage = localStorage.getItem('preferredLanguage') || 'en'; // Get preferred language
      await fetchTranslations(storedLanguage); // Fetch translations for the stored language
      await fetchCities();
    });

    return {
      selectedCity,
      cities,
      error,
      translations,
      currentLanguage,
      goToDetailForm,
      switchLanguage,
    };
  },
};
</script>

<style scoped>
.city-selection-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f0f2f5;
}

.card {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  padding: 2rem;
  width: 100%;
  max-width: 400px;
}

.city-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-label {
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.form-control {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ced4da;
  border-radius: 4px;
}

.btn {
  padding: 0.5rem 1rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn:disabled {
  background-color: #6c757d;
  cursor: not-allowed;
}

.alert-danger {
  color: #721c24;
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
  padding: 0.75rem;
  border-radius: 4px;
  margin-bottom: 1rem;
}

.debug-info {
  background-color: #f4f4f4;
  border-radius: 4px;
  padding: 1rem;
  font-family: monospace;
  max-height: 300px;
  overflow-y: auto;
}
</style>
