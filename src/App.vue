<!--
  - SPDX-FileCopyrightText: 2026 Kars van Velzen
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->

<template>
  <NcContent app-name="bookshelfs">
    <Nav @createBook="createBook" @reset="reset" @reStyle="reStyle"/>
    <NcAppContent>
        <Shelf ref="bookshelf" :books="books" @select="select" @updateBook="updateBook"/>
    </NcAppContent>
    <Sidebar ref="sidebar" @deleteBook="deleteBook" @updateBook="updateBook"/>
  </NcContent>
</template>

<script lang="ts">

import NcAppContent from '@nextcloud/vue/components/NcAppContent'
import Nav from "./components/Nav.vue";
import NcContent from "@nextcloud/vue/components/NcContent";
import Sidebar from "./components/Sidebar.vue";
import Shelf from "./components/Shelf.vue";
import {type Book, constructBook} from "./models/Book.ts";
import {loadState} from "@nextcloud/initial-state";
import {generateOcsUrl} from "@nextcloud/router";
import axios from "@nextcloud/axios";
import {showError} from "@nextcloud/dialogs";
import {translate} from "@nextcloud/l10n";
import {getRandomHeight, randomColor, randomPattern} from "./utils.ts";

export default {
	components: {
    Sidebar,
    NcContent,
		NcAppContent,
		Shelf,
    Nav,
	},

  data() {
    const state = loadState<{ books: Book[] }>('bookshelfs', 'bookshelfs-initial-state')
    const books = (state.books || []).sort((a: Book, b: Book) => a.position - b.position);
    return {
      books,
    }
  },

  methods: {
    createBook(book: Book) {
      book.position = this.books.length
      axios.post(generateOcsUrl('apps/bookshelfs/api/v1/books'), book).then(response => {
        this.books.push(constructBook(response.data.ocs.data))
      }).catch((error) => {
        showError(translate('bookshelfs', 'Error adding book'))
        console.error(error)
      })
    },
    updateBook(options: Partial<Book>, local = true) {
      axios.put(generateOcsUrl(`apps/bookshelfs/api/v1/books/${options.id}`), options).then(response => {
        if (local) {
          const book = constructBook(response.data.ocs.data)
          const idx = this.books.findIndex((b: Book) => b.id === book.id)
          if (idx !== -1) {
            this.books[idx] = book
          }
        }
      }).catch((error) => {
        showError(translate('bookshelfs', 'Error updating book'))
        console.error(error)
      })
    },
    deleteBook(id: number, local = true) {
      const options: Partial<Book> = {id: id}
      axios.delete(generateOcsUrl('apps/bookshelfs/api/v1/books/' + id), options).then(() => {
        if (local) {
          const idx = this.books.findIndex((b: Book) => b.id === id)
          if (idx !== -1) {
            this.books.splice(idx, 1)
          }
        }
      }).catch((error) => {
        showError(translate('bookshelfs', 'Error deleting book'))
        console.error(error)
      })
    },
    select(book: Book) {
      let sidebar = this.$refs.sidebar as InstanceType<typeof Sidebar>
      sidebar.select(book);
    },
    reStyle() {
      this.books.forEach((book: Book) => {
        book.colour = randomColor();
        book.pattern = randomPattern();
        book.height = getRandomHeight();
        this.updateBook(book)
      })
    },
    reset() {
      this.books.forEach((book: Book) => {
        this.deleteBook(book.id, false)
      })
      this.books = []
    },
  }
}
</script>

<style scoped lang="scss">
.app-content {
  height: 100vh;
  width: 100vw;
}
</style>
