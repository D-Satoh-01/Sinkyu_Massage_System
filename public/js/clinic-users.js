//-- public/js/cui-registration.js --//


// 郵便番号から住所を検索する関数
async function searchAddress() {
  await searchAndFillAddress('postal_code', {
    combined: false,
    address1: 'address_1',
    address2: 'address_2'
  });
}

// メッセージ表示関数
function showMessage(message, type) {
  const messageEl = document.getElementById('address-message');
  messageEl.textContent = message;
  messageEl.className = type;
  messageEl.style.display = 'block';

  // エラーメッセージは5秒後に消す
  if (type === 'error') {
  setTimeout(() => {
    messageEl.style.display = 'none';
  }, 5000);
  }
}

// 生年月日から年齢を計算する関数
function calculateAge() {
  const birthdayInput = document.getElementById('birthday');
  const ageInput = document.getElementById('age');

  if (!birthdayInput.value) {
    ageInput.value = '';
    return;
  }

  const birthday = new Date(birthdayInput.value);
  const today = new Date();

  let age = today.getFullYear() - birthday.getFullYear();
  const monthDiff = today.getMonth() - birthday.getMonth();

  // 誕生日がまだ来ていない場合は年齢を1減らす
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
    age--;
  }

  ageInput.value = age >= 0 ? age : '';
}

// ページ読み込み時に実行
document.addEventListener('DOMContentLoaded', function() {
  // 郵便番号入力時の処理
  setupPostalCodeInput('postal_code', searchAddress);

  // 生年月日入力時に年齢を自動計算
  const birthdayInput = document.getElementById('birthday');
  if (birthdayInput) {
    birthdayInput.addEventListener('change', calculateAge);

    // 既に生年月日が入力されていれば年齢を計算
    if (birthdayInput.value) {
      calculateAge();
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
