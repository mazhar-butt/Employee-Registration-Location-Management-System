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

    <div class="search-container mb-6 text-center">
      <input
        type="text"
        v-model="searchQuery"
        @input="filterStates"
        :placeholder="translations.search_placeholder || 'Search...'"
        class="search-input"
      />
    </div>

    <h1 class="text-2xl font-bold mb-6 text-center">{{ translations.states_list || 'States List' }}</h1>
    <button @click="navigateTo('detail')" class="edit-button mb-4 bg-blue-500 text-white px-4 py-2 rounded">
      {{ translations.back || 'Back' }}
    </button>

    <table class="w-full table-auto border-collapse border border-gray-300 shadow-lg rounded-lg">
      <thead class="bg-gray-200">
        <tr>
          <th 
            class="border border-gray-300 px-4 py-2 cursor-pointer hover:bg-gray-300"
            @click="sortTable('id')"
          >
            {{ translations.id || 'ID' }}
            <span v-if="sortColumn === 'id'">
              {{ sortOrder === 'asc' ? '↑' : '↓' }}
            </span>
          </th>
          <th 
            class="border border-gray-300 px-4 py-2 cursor-pointer hover:bg-gray-300"
            @click="sortTable('name')"
          >
            {{ translations.state || 'State Name' }}
            <span v-if="sortColumn === 'name'">
              {{ sortOrder === 'asc' ? '↑' : '↓' }}
            </span>
          </th>
          <th 
            class="border border-gray-300 px-4 py-2 cursor-pointer hover:bg-gray-300"
            @click="sortTable('created_at')"
          >
            {{ translations.created_at || 'Created At' }}
            <span v-if="sortColumn === 'created_at'">
              {{ sortOrder === 'asc' ? '↑' : '↓' }}
            </span>
          </th>
          <th 
            class="border border-gray-300 px-4 py-2 cursor-pointer hover:bg-gray-300"
            @click="sortTable('updated_at')"
          >
            {{ translations.updated_at || 'Updated At' }}
            <span v-if="sortColumn === 'updated_at'">
              {{ sortOrder === 'asc' ? '↑' : '↓' }}
            </span>
          </th>
          <th class="border border-gray-300 px-4 py-2">{{ translations.actions || 'Actions' }}</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="state in sortedAndFilteredStates"
          :key="state.id"
          class="hover:bg-gray-100 transition"
        >
          <td class="border border-gray-300 px-4 py-2 text-center">{{ state.id }}</td>
          <td class="border border-gray-300 px-4 py-2">
            <template v-if="editedState.id === state.id">
              <input
                v-model="editedState.name"
                class="w-full px-2 py-1 border border-gray-300 rounded"
                :placeholder="translations.enter_state_name || 'Enter state name'"
                @keyup.enter="saveEdit(state.id)"
              />
            </template>
            <template v-else>
              {{ state.name }}
            </template>
          </td>
          <td class="border border-gray-300 px-4 py-2 text-center">
            {{ formatDate(state.created_at) }}
          </td>
          <td class="border border-gray-300 px-4 py-2 text-center">
            {{ formatDate(state.updated_at) }}
          </td>
          <td class="border border-gray-300 px-4 py-2 text-center">
            <template v-if="editedState.id === state.id">
              <button 
                @click="saveEdit(state.id)" 
                class="bg-green-500 text-white px-3 py-1 rounded mr-2"
              >
                {{ translations.save || 'Save' }}
              </button>
              <button 
                @click="cancelEdit" 
                class="bg-gray-500 text-white px-3 py-1 rounded"
              >
                {{ translations.cancel || 'Cancel' }}
              </button>
            </template>
            <template v-else>
              <button 
                @click="editState(state)" 
                class="bg-blue-500 text-white px-3 py-1 rounded mr-2"
              >
                {{ translations.edit || 'Edit' }}
              </button>
              <button 
                @click="confirmDelete(state.id)" 
                class="bg-red-500 text-white px-3 py-1 rounded"
              >
                {{ translations.delete || 'Delete' }}
              </button>
            </template>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <hr>
    <div class="pagination-container">
      <button 
        @click="prevPage" 
        :disabled="currentPage === 1" 
        class="pagination-button"
      >
        {{ translations.previous || 'Previous' }}
      </button>
      <span class="pagination-text">
        {{ translations.page || 'Page' }} {{ currentPage }} {{ translations.of || 'of' }} {{ totalPages }}
      </span>
      <button 
        @click="nextPage" 
        :disabled="currentPage === totalPages" 
        class="pagination-button"
      >
        {{ translations.next || 'Next' }}
      </button>
    </div>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import axios from "axios";

export default {
  props: {
    initialStates: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      states: [...this.initialStates],
      filteredStates: [...this.initialStates], // Filtered list for search
      editedState: { id: null, name: "" },
      loading: false,
      translations: {},
      currentLanguage: 'en',
      sortColumn: 'id',
      sortOrder: 'asc',
      currentPage: 1,
      itemsPerPage: 7,
      searchQuery: "", // Search query
    };
  },
  computed: {
    sortedAndFilteredStates() {
      // Filter states based on search query
      const query = this.searchQuery.trim().toLowerCase();
      const filtered = this.states.filter(state =>
        state.name.toLowerCase().includes(query)
      );

      // Sort filtered states
      const sorted = filtered.sort((a, b) => {
        const valueA = a[this.sortColumn];
        const valueB = b[this.sortColumn];

        if (valueA < valueB) {
          return this.sortOrder === 'asc' ? -1 : 1;
        }
        if (valueA > valueB) {
          return this.sortOrder === 'asc' ? 1 : -1;
        }
        return 0;
      });

      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return sorted.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.states.filter(state =>
        state.name.toLowerCase().includes(this.searchQuery.trim().toLowerCase())
      ).length / this.itemsPerPage);
    },
  },
  methods: {
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    sortTable(column) {
      if (this.sortColumn === column) {
        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortColumn = column;
        this.sortOrder = 'asc';
      }
    },
    formatDate(date) {
      return date ? new Date(date).toLocaleDateString() : "N/A";
    },
    switchLanguage(lang) {
      this.currentLanguage = lang;
      localStorage.setItem('preferredLanguage', lang);
      this.fetchTranslations(lang);
    },
    async fetchTranslations(locale) {
      try {
        const response = await axios.get(`/translations/${locale}`);
        this.translations = response.data;
        this.currentLanguage = locale;
      } catch (error) {
        console.error("Error fetching translations:", error);
      }
    },
    editState(state) {
      this.cancelEdit();
      this.editedState = { ...state };
    },
    navigateTo(routeName) {
      try {
        this.$router.push({ name: routeName });
      } catch (error) {
        console.error("Navigation error:", error);
        window.location.href = `/${routeName}`;
      }
    },
    async saveEdit(id) {
      const state = this.states.find((s) => s.id === id);
      if (state && this.editedState.name.trim()) { 
        try {
          const response = await axios.put(`/states/${id}`, {
            name: this.editedState.name.trim()
          });
          
          Swal.fire({
            title: this.translations.success || 'Success!',
            text: this.translations.state_updated || 'State updated successfully',
            icon: 'success',
            confirmButtonText: this.translations.ok_button_text || 'OK'
          }).then(() => {
            const index = this.states.findIndex(s => s.id === id);
            if (index !== -1) {
              this.states[index] = {
                ...this.states[index],
                name: this.editedState.name.trim(),
                updated_at: new Date().toISOString()
              };
            }
            
            this.cancelEdit();
            window.location.reload();
          });
        } catch (error) {
          if (error.response && error.response.status === 422) {
            const errors = error.response.data.errors;
            Swal.fire({
              title: this.translations.validation_error || 'Validation Error',
              text: errors.name ? errors.name[0] : this.translations.invalid_input || 'Invalid input',
              icon: 'error'
            });
          } else {
            Swal.fire({
              title: this.translations.error || 'Error',
              text: error.response?.data?.message || this.translations.an_error_occurred || 'An error occurred',
              icon: 'error'
            });
          }
        }
      } else {
        Swal.fire({
          title: this.translations.error || 'Error',
          text: this.translations.state_name_empty || 'State name cannot be empty',
          icon: 'error'
        });
      }
    },
    cancelEdit() {
      this.editedState = { id: null, name: "" };
    },
    async confirmDelete(id) {
      const state = this.states.find((s) => s.id === id);

      const result = await Swal.fire({
        title: this.translations.confirm_delete || "Are you sure?",
        text: `${this.translations.delete_state || 'Do you want to delete'} "${state.name}"?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: this.translations.confirm_button_text || "Yes, delete it!",
        cancelButtonText: this.translations.cancel_button_text || "Cancel"
      });

      if (result.isConfirmed) {
        this.deleteState(id);
      }
    },
    async deleteState(id) {
      try {
        const response = await axios.delete(`/states/${id}`);
        const { success, message } = response.data;

        if (success) {
          this.states = this.states.filter((state) => state.id !== id);
          
          await Swal.fire({
            title: this.translations.deleted_message || 'Deleted!',
            text: this.translations.state_deleted || 'State deleted successfully.',
            icon: 'success',
            confirmButtonColor: '#3085d6'
          });
          
          this.$router.go(0);
        } else {
          await Swal.fire({
            title: this.translations.error || 'Error',
            text: message || this.translations.failed_delete_state || 'Failed to delete the state.',
            icon: 'error',
            confirmButtonColor: '#3085d6'
          });
        }
      } catch (error) {
        const errorMessage = error.response?.data?.message || 
                             this.translations.an_unexpected_error || 
                             'An unexpected error occurred while deleting the state.';
        
        await Swal.fire({
          title: this.translations.error || 'Error',
          text: errorMessage,
          icon: 'error',
          confirmButtonColor: '#3085d6'
        });
      }
    }
  },
  mounted() {
    const storedLanguage = localStorage.getItem('preferredLanguage');
    if (storedLanguage) {
        this.currentLanguage = storedLanguage;
        this.fetchTranslations(storedLanguage);
    } else {
        this.fetchTranslations('en');
    }
  }
};
</script>

