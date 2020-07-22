 <!--
  - @copyright Copyright (c) 2020 John Molakvoæ <skjnldsv@protonmail.com>
  -
  - @author John Molakvoæ <skjnldsv@protonmail.com>
  -
  - @license GNU AGPL version 3 or any later version
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program.  If not, see <http://www.gnu.org/licenses/>.
  -
  -->
<template>
	<HeaderMenu id="unified-search" class="unified-search" @open="onOpen">
		<!-- Header icon -->
		<template #trigger>
			<span class="icon-search-white" />
		</template>

		<!-- Search input -->
		<div class="unified-search__input-wrapper">
			<input ref="input"
				v-model="query"
				class="unified-search__input"
				type="search"
				:placeholder="t('core', 'Search for {types} …', { types: formattedTypes })"
				@input="onInputDebounced">
		</div>

		<!-- Grouped search results -->
		<ul v-for="(list, type) in results"
			:key="type"
			class="unified-search__results"
			:class="`unified-search__results-${type}`">
			<!-- Search results -->
			<SearchResult v-for="result in list" :key="result.resourceUrl" v-bind="result">
				<ActionLink icon="icon-external" :href="result.resourceUrl" />
			</SearchResult>

			<!-- Load more button -->
			<SearchResult :title="loading[type]
					? t('core', 'Load more results …')
					: t('core', 'Loading more results …')"
				:icon="loading[type] ? 'icon-loading-small' : ''"
				@click="loadMore(type)">
				<ActionButton icon="icon-add" @click.stop="loadMore(type)" />
			</SearchResult>
		</ul>
	</HeaderMenu>
</template>

<script>
import { getTypes, search, defaultLimit } from '../services/UnifiedSearchService'
import ActionButton from '@nextcloud/vue/dist/Components/ActionButton'
import ActionLink from '@nextcloud/vue/dist/Components/ActionLink'
import EmptyContent from '@nextcloud/vue/dist/Components/EmptyContent'

import debounce from 'debounce'

import HeaderMenu from '../components/HeaderMenu'
import SearchResult from '../components/UnifiedSearch/SearchResult'

const minSearchLength = 2

export default {
	name: 'UnifiedSearch',

	components: {
		ActionButton,
		ActionLink,
		EmptyContent,
		HeaderMenu,
		SearchResult,
	},

	data() {
		return {
			types: [],
			results: {},
			loading: {},
			query: '',
			defaultLimit,
			minSearchLength,
		}
	},

	computed: {
		formattedTypes() {
			return this.types.map(type => type.charAt(0).toUpperCase() + type.substr(1).toLowerCase()).join(', ')
		},
		hasResults() {
			return Object.keys(this.results).length !== 0
		},
	},

	async created() {
		this.types = await getTypes()
		console.debug('Unified Search initialized with the following providers', this.types)
	},

	methods: {
		async onOpen() {
			this.focusInput()

			// Update types list in the background
			this.types = await getTypes()
		},

		focusInput() {
			this.$nextTick(() => {
				this.$refs.input.focus()
				this.$refs.input.select()
			})
		},

		async onInput(e) {
			// Do not search if not long enough
			if (this.query && this.query.trim().length < minSearchLength) {
				return
			}

			this.types.forEach(async type => {
				const request = await search(type, this.query)
				this.$set(this.results, type, request.data.entries)
			})
		},

		onInputDebounced: debounce(function(e) {
			this.onInput(e)
		}, 200),

		loadMore(type) {
			this.$set(this.loading, type, true)
		},
	},
}
</script>

<style lang="scss" scoped>
.unified-search {
	&__input-wrapper {
		position: sticky;
		// above search results
		z-index: 2;
		top: 0;
		background-color: var(--color-main-background);
	}

	&__input {
		width: calc(100% - 2 * 8px);
		height: 34px;
		margin: 8px;
	}
}

</style>
