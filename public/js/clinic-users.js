//-- public/js/clinic-users.js --//


// 郵便番号から住所を検索する関数
async function searchAddress() {
  await searchAndFillAddress('postal_code', {
    combined: false,
    address1: 'address_1',
    address2: 'address_2'
  });
}

// ページ読み込み時に実行
document.addEventListener('DOMContentLoaded', function() {
  // 郵便番号入力時の処理
  setupPostalCodeInput('postal_code', searchAddress);

  // 生年月日入力時に年齢を自動計算
  const birthdayInput = document.getElementById('birthday');
  if (birthdayInput) {
    birthdayInput.addEventListener('change', () => calculateAndFillAge('birthday', 'age'));

    // 既に生年月日が入力されていれば年齢を計算
    if (birthdayInput.value) {
      calculateAndFillAge('birthday', 'age');
    }
  }

  // カスタムツールチップの実装
  const ageInput = document.getElementById('age');
  const tooltip = document.getElementById('age-tooltip');

  if (ageInput && tooltip) {
    // マウスオーバーで即座にツールチップを表示
    ageInput.addEventListener('mouseenter', function(e) {
      const tooltipText = this.getAttribute('data-tooltip');
      if (tooltipText) {
        tooltip.textContent = tooltipText;
        tooltip.style.display = 'block';

        // 初期位置をカーソル位置に設定
        const rect = this.getBoundingClientRect();
        tooltip.style.position = 'fixed';
        tooltip.style.top = (e.clientY + 15) + 'px';
        tooltip.style.left = (e.clientX + 10) + 'px';
      }
    });

    // マウス移動でツールチップをカーソルに追従
    ageInput.addEventListener('mousemove', function(e) {
      if (tooltip.style.display === 'block') {
        tooltip.style.top = (e.clientY + 15) + 'px';
        tooltip.style.left = (e.clientX + 10) + 'px';
      }
    });

    // マウスアウトでツールチップを非表示
    ageInput.addEventListener('mouseleave', function() {
      tooltip.style.display = 'none';
    });
  }
});
