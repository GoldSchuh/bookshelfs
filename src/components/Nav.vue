<template>
  <NcAppNavigation>
    <!-- Form to add a new book -->
    <div class="add-book-form">
      <input
          v-model="title"
          type="text"
          placeholder="Enter Book Title"
          class="input-title"
      />
      <input
          v-model="author"
          type="text"
          placeholder="Enter Book Author"
          class="input-author"
      />
      <input
          v-model="url"
          type="text"
          placeholder="Enter Picture URL or select from your Nextcloud instance on the right"
      />
        <button
            type="button"
            title="'Pick a local image'"
            @click="pickImage"
        >
          <span class="icon-category-multimedia"></span>
        </button>
<!--      TODO show actual file or image instead of path/id (make issue)-->
      <input
          v-model="file"
          type="text"
          placeholder="Enter File URL or select from your Nextcloud instance on the right"
      />
        <button
            type="button"
            title="'Pick a local file'"
            @click="pickEbook"
        >
                  <span class="icon-category-files"></span>
        </button>
      <button @click="addBook" class="book-add">Add</button>
    </div>
  </NcAppNavigation>
</template>

<script>
import NcAppNavigation from '@nextcloud/vue/components/NcAppNavigation'
import { translate } from '@nextcloud/l10n'
import {getFilePickerBuilder} from '@nextcloud/dialogs';

export default {
  name: 'Nav',

  components: {
    NcAppNavigation,
  },

  data() {
    return {
      title: '',
      author: '',
      url: '',
      file: '',
      file_name: ''
    }
  },
  methods: {
    addBook() {
      if (!this.title || !this.author) {
        this.title = 'a';
        this.author = 'a';
      }
      this.$emit('add-book', {
        title: this.title,
        author: this.author,
        url: this.url,
        file: this.file
      })
      this.title = ''
      this.author = ''
      this.url = ''
      this.file = ''
    },
    pickImage() {
      getFilePickerBuilder('Path to your book cover image',)
          .addMimeTypeFilter('image/jpeg')
          .addMimeTypeFilter('image/png')
          .addButton({
            label: 'Choose',
            variant: 'primary',
            callback: (selectedPaths) => {
              this.url = selectedPaths[0]._data.attributes.filename || '';
            }
          })
          .build().pick();
    },
    pickEbook() {
      getFilePickerBuilder('Path to your book',)
          .addButton({
            label: 'Choose',
            variant: 'primary',
            callback: (selectedPaths) => {
              this.file = selectedPaths[0]._data.id || -1;
            }
          })
          .build().pick();
    }
  }
}
</script>

<style scoped lang="scss">
/* Input */
//.add-book-form {
//  margin-bottom: 10px;
//  display: flex;
//  gap: 5px;
//}
//.input-title,
//.input-author {
//  padding: 8px;
//  font-size: 14px;
//  border: 1px solid #ccc;
//  border-radius: 4px;
//  width: 20vw;
//  max-width: 160px;
//  max-height: 16px;
//}
//.book-add {
//  color: white;
//  border: 1px solid #ccc;
//  border-radius: 4px;
//  cursor: pointer;
//  max-height: 16px;
//}
//.book-add:hover {
//  background-color: #45a049;
//}
</style>
