let currentPerPage = window.initialLimit || 10;
let currentSearch = '';
let currentSort = window.initialSort || 'id';
let currentOrder = window.initialOrder || 'desc';

document.addEventListener('DOMContentLoaded', function() {
  // ソートのイベントリスナー
  document.querySelectorAll('[data-sort]').forEach(function(link) {
  link.addEventListener('click', function(e) {
    e.preventDefault();
    const sortBy = this.getAttribute('data-sort');

    if (currentSort === sortBy) {
    currentOrder = currentOrder === 'asc' ? 'desc' : 'asc';
    } else {
    currentSort = sortBy;
    currentOrder = 'asc';
    }

    loadData();
  });
  });

  // 削除ボタンのイベントリスナー
  document.addEventListener('submit', function(e) {
  if (e.target.classList.contains('delete-form')) {
    e.preventDefault();

    if (confirm('一度削除したデータは元に戻せません。\n削除してもよろしいですか？')) {
    e.target.submit();
    }
  }
  });

  // 初期ページネーションリンクにイベントリスナーを追加
  const paginationDiv = document.querySelector('.pagination-container');
  if (paginationDiv) {
  paginationDiv.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', function(e) {
    e.preventDefault();
    const url = new URL(this.href);
    const page = url.searchParams.get('page');
    loadData(page);
    });
  });
  }
});

function changePerPage(value) {
  currentPerPage = parseInt(value);
  loadData();
}

function changeSearch(value) {
  currentSearch = value;
  loadData();
}

function loadData(page = 1) {
  const url = new URL(window.location);
  url.searchParams.set('per_page', currentPerPage);
  url.searchParams.set('search', currentSearch);
  url.searchParams.set('sort_by', currentSort);
  url.searchParams.set('sort_order', currentOrder);
  url.searchParams.set('page', page);

  fetch(url, {
  method: 'GET',
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
  },
  })
  .then(response => response.json())
  .then(data => {
  updateTable(data.clinicUsers);
  updatePagination(data.pagination);
  updateSortIndicators(data.sortBy, data.sortOrder);
  window.scrollTo({ top: 0, behavior: 'smooth' }); // スクロール位置をスムーズにリセット
  })
  .catch(error => console.error('Error:', error));
}

function updateTable(clinicUsers) {
  const tbody = document.getElementById('tableBody');
  tbody.innerHTML = '';

  if (clinicUsers.length === 0) {
  tbody.innerHTML = '<tr><td colspan="7" style="border: 1px solid #000; padding: 8px; text-align: center;">データがありません</td></tr>';
  } else {
  clinicUsers.forEach(function(user) {
    const row = document.createElement('tr');
    row.setAttribute('data-id', user.id);
    row.setAttribute('data-name', user.clinic_user_name);
    row.setAttribute('data-furigana', user.furigana);
    row.setAttribute('data-birthday', user.birthday ? new Date(user.birthday).toLocaleDateString('ja-JP') : '');
    row.setAttribute('data-address', (user.postal_code ? '〒' + user.postal_code : '') + user.address_1 + user.address_2 + user.address_3);
    row.setAttribute('data-created', new Date(user.created_at).toLocaleString('ja-JP'));

    row.innerHTML = `
    <td style="border: 1px solid #000; padding: 8px;">${user.id}</td>
    <td style="border: 1px solid #000; padding: 8px;">
      <a href="/clinic-users-info/edit/${user.id}">${user.clinic_user_name} [編集]</a><br>
      ${user.furigana}
    </td>
    <td style="border: 1px solid #000; padding: 8px;">
      ${user.birthday ? new Date(user.birthday).toLocaleDateString('ja-JP') + ' (' + calculateAge(user.birthday) + '才)' : ''}
    </td>
    <td style="border: 1px solid #000; padding: 8px;">
      ${user.postal_code ? '〒' + user.postal_code + '<br>' : ''}
      ${user.address_1} ${user.address_2} ${user.address_3}
    </td>
    <td style="border: 1px solid #000; padding: 8px;">
      ${new Date(user.created_at).toLocaleDateString('ja-JP')}<br>
      ${new Date(user.created_at).toLocaleTimeString('ja-JP', {hour: '2-digit', minute: '2-digit'})}
    </td>
    <td style="border: 1px solid #000; padding: 8px;">
      保険情報一覧<br>
      同意医師履歴（あんま・マッサージ）<br>
      同意医師履歴（はり・きゅう）<br>
      計画書情報
    </td>
    <td style="border: 1px solid #000; padding: 8px;">
      <form action="/clinic-users-info/delete/${user.id}" method="POST" class="delete-form" style="display: inline;">
      <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
      <input type="hidden" name="_method" value="DELETE">
      <button type="submit" class="delete-btn" style="background: none; border: none; color: #0d6efd; cursor: pointer;">削除</button>
      </form>
    </td>
    `;
    tbody.appendChild(row);
  });
  }
}

function updatePagination(pagination) {
  const paginationDiv = document.querySelector('.pagination-container') || document.createElement('div');
  paginationDiv.className = 'pagination-container';
  paginationDiv.innerHTML = pagination.links;
  const existing = document.querySelector('.pagination-container');
  if (existing) {
  existing.replaceWith(paginationDiv);
  } else {
  document.querySelector('br:last-of-type').after(paginationDiv);
  }

  // ページネーションリンクにイベントリスナーを追加
  paginationDiv.querySelectorAll('a').forEach(link => {
  link.addEventListener('click', function(e) {
    e.preventDefault();
    const url = new URL(this.href);
    const page = url.searchParams.get('page');
    window.scrollTo({ top: 0, behavior: 'smooth' }); // スクロール位置をスムーズにリセット
    loadData(page);
  });
  });
}

function updateSortIndicators(sortBy, sortOrder) {
  document.querySelectorAll('[id^="sort-"]').forEach(function(span) {
  span.textContent = '';
  });

  const indicator = document.getElementById('sort-' + sortBy);
  if (indicator) {
  indicator.textContent = sortOrder === 'asc' ? '▴' : '▾';
  }
}

function calculateAge(birthday) {
  const birth = new Date(birthday);
  const today = new Date();
  let age = today.getFullYear() - birth.getFullYear();
  const monthDiff = today.getMonth() - birth.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
  age--;
  }
  return age;
}
