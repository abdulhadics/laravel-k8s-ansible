#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
if ! command -v ansible-playbook >/dev/null 2>&1; then
  sudo apt-get update
  sudo apt-get install -y ansible
fi
ansible-playbook -i ansible/inventory.ini ansible/deploy.yml --ask-become-pass "$@"
