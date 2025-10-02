// public/js/cui-home.js

let allRows = [];
let currentSort = window.initialSort || 'id';
let currentOrder = window.initialOrder || 'desc';
let displayLimit = window.initialLimit || 10;

// ページ読み込み時に全行を保存
document.addEventListener('DOMContentLoaded', function() {
  const tbody = document.getElementById('tableBody');
  allRows = Array.from(tbody.querySelectorAll('tr:not(#noDataRow)'));

  // 検索入力のイベントリスナー
  document.getElementById('search').addEventListener('input', function(e) {
    filterAndDisplay(e.target.value);
  });

  // 表示件数変更のイベントリスナー
  document.getElementById('per_page').addEventListener('change', function(e) {
    displayLimit = parseInt(e.target.value);
    filterAndDisplay(document.getElementById('search').value);
  });

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

      updateSortIndicators();
      filterAndDisplay(document.getElementById('search').value);
    });
  });

  // 削除ボタンのイベントリスナー
  document.querySelectorAll('.delete-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      if (confirm('一度削除したデータは元に戻せません。\n削除してもよろしいですか？')) {
        this.submit();
      }
    });
  });
});

function filterAndDisplay(searchTerm) {
  const tbody = document.getElementById('tableBody');
  const lowerSearch = searchTerm.toLowerCase();

  let filteredRows = allRows.filter(function(row) {
    if (!searchTerm) return true;

    const id = row.getAttribute('data-id').toLowerCase();
    const name = row.getAttribute('data-name').toLowerCase();
    const furigana = row.getAttribute('data-furigana').toLowerCase();
    const birthday = row.getAttribute('data-birthday').toLowerCase();
    const address = row.getAttribute('data-address').toLowerCase();
    const created = row.getAttribute('data-created').toLowerCase();

    return id.includes(lowerSearch) || 
           name.includes(lowerSearch) || 
           furigana.includes(lowerSearch) ||
           birthday.includes(lowerSearch) || 
           address.includes(lowerSearch) || 
           created.includes(lowerSearch);
  });

  filteredRows.sort(function(a, b) {
    let aVal, bVal;

    switch(currentSort) {
      case 'id':
        aVal = parseInt(a.getAttribute('data-id'));
        bVal = parseInt(b.getAttribute('data-id'));
        break;
      case 'clinic_user_name':
        aVal = a.getAttribute('data-name');
        bVal = b.getAttribute('data-name');
        break;
      case 'birthday':
        aVal = a.getAttribute('data-birthday');
        bVal = b.getAttribute('data-birthday');
        break;
      case 'address_1':
        aVal = a.getAttribute('data-address');
        bVal = b.getAttribute('data-address');
        break;
      case 'created_at':
        aVal = a.getAttribute('data-created');
        bVal = b.getAttribute('data-created');
        break;
      default:
        aVal = a.getAttribute('data-id');
        bVal = b.getAttribute('data-id');
    }

    if (aVal < bVal) return currentOrder === 'asc' ? -1 : 1;
    if (aVal > bVal) return currentOrder === 'asc' ? 1 : -1;
    return 0;
  });

  const displayRows = filteredRows.slice(0, displayLimit);

  tbody.innerHTML = '';
  if (displayRows.length === 0) {
    tbody.innerHTML = '<tr><td colspan="7" style="border: 1px solid #000; padding: 8px; text-align: center;">データがありません</td></tr>';
  } else {
    displayRows.forEach(function(row) {
      tbody.appendChild(row.cloneNode(true));
    });
    
    // 削除ボタンのイベントリスナーを再設定
    document.querySelectorAll('.delete-form').forEach(function(form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (confirm('一度削除したデータは元に戻せません。\n削除してもよろしいですか？')) {
          this.submit();
        }
      });
    });
  }
}

function updateSortIndicators() {
  document.querySelectorAll('[id^="sort-"]').forEach(function(span) {
    span.textContent = '';
  });

  const indicator = document.getElementById('sort-' + currentSort);
  if (indicator) {
    indicator.textContent = currentOrder === 'asc' ? '▴' : '▾';
  }
}